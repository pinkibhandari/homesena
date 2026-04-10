<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Services\ExpertSlotService;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\BookingResource;
use App\Services\FirebaseService;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Address;
use App\Models\BookingSlot;

class BookingController extends Controller
{

    public function storeBooking(Request $request)
    {
        $validator = $this->validateBookingRequest($request);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => [],
            ], 422);
        }

        if ($request->date == Carbon::today()->toDateString()) {
            if ($request->time < Carbon::now()->format('H:i')) {
                return response()->json([
                    'message' => 'Time cannot be in the past.'
                ], 422);
            }
        }

        $address = Address::where('id', $request->addressId)
            ->where('user_id', auth()->id())
            ->first();
        if (!$address || empty($address->address_lat) || empty($address->address_long)) {
            return response()->json([
                'code' => 422,
                'data' => [],
                'success' => false,
                'message' => 'Invalid address or missing location.'
            ], 422);
        }

        if ($this->bookingExists($request)) {
            return response()->json([
                'code' => 422,
                'data' => [],
                'status' => false,
                'message' => 'Booking already exists for this slot and user'
            ]);
        }

        $booking = $this->createBooking($request);
        $dates = $this->generateBookingDates($request);
        $slots = $this->generateBookingSlots($booking, $dates, $request);

        BookingSlot::insertOrIgnore($slots);

        $result = $this->processBookingNotifications($booking, $slots, $address);

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $result['message'],
            'data' => [
                'booking' => new BookingResource($booking),
            ]
        ]);
    }

    private function validateBookingRequest(Request $request)
    {
        return Validator::make($request->all(), [
            // 'serviceId' => 'required_if:type,scheduled',
            'serviceId' => 'nullable|exists:services,id',
            'addressId' => 'required|exists:addresses,id',
            'type' => 'required|in:instant,scheduled',
            'booking_subtype' => 'required_if:type,scheduled|in:single,recurring',
            'time' => 'required',
            // 'date' => 'required_if:booking_subtype,single|date',
            //  prevent past date for single booking
            'date' => 'required_if:booking_subtype,single|date|after_or_equal:today',
            // 'start_date' => 'required_if:booking_subtype,recurring|date',
            //  prevent past start date
            'start_date' => 'required_if:booking_subtype,recurring|date|after_or_equal:today',
            'end_date' => 'required_if:booking_subtype,recurring|date|after_or_equal:start_date',
            'recurring_type' => 'required_if:booking_subtype,recurring|in:daily,weekly,monthly',
            'days' => 'required_if:recurring_type,weekly|array',
            'days.*' => 'in:mon,tue,wed,thu,fri,sat,sun',
            'monthly_date' => 'nullable|integer|min:1|max:31',
            'week' => 'nullable|integer|min:1|max:5',
            'day' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);


    }

    private function bookingExists(Request $request): bool
    {
        $query = Booking::where('user_id', auth()->id())
            ->where('type', $request->type)
            ->where('time', $request->time)
            ->where('address_id', $request->addressId)
            ->when($request->serviceId, function ($q) use ($request) {
                $q->where('service_id', $request->serviceId);
            });

        if ($request->booking_subtype === 'single') {
            $query->where('booking_subtype', 'single')->whereDate('start_date', $request->date);
        } elseif ($request->booking_subtype === 'recurring') {
            $query->where('booking_subtype', 'recurring')
                ->whereDate('start_date', $request->start_date)
                ->whereDate('end_date', $request->end_date);
        } elseif ($request->type === 'instant') {
            $query->whereDate('start_date', $request->start_date ?? now());
        }

        return $query->exists();
    }

    private function createBooking(Request $request): Booking
    {
        $startDate = null;
        $endDate = null;

        if ($request->type === 'instant') {
            $startDate = now();
            $endDate = now();
        } elseif ($request->booking_subtype === 'single') {
            $startDate = $request->date;
            $endDate = $request->date;
        } elseif ($request->booking_subtype === 'recurring') {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }

        return Booking::create([
            'booking_code' => 'HS-' . now()->format('md') . strtoupper(Str::random(4)),
            'user_id' => auth()->id(),
            'service_id' => $request->serviceId,
            'type' => $request->type,
            'booking_subtype' => $request->booking_subtype,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'time' => $request->time,
            'status' => 'pending',
            'address_id' => $request->addressId,
            'total_price' => $request->total_price,
        ]);
    }

    private function generateBookingDates(Request $request): array
    {
        $dates = [];

        if ($request->type === 'instant') {
            $dates[] = now();
        } elseif ($request->booking_subtype === 'single') {
            $dates[] = Carbon::parse($request->date);
        } else {
            $dates = $this->generateRecurringDates($request);
        }

        return $dates;
    }

    private function generateRecurringDates(Request $request): array
    {
        $dates = [];
        $current = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);

        if ($request->recurring_type === 'daily') {
            while ($current <= $end) {
                $dates[] = $current->copy();
                $current->addDay();
            }
        } elseif ($request->recurring_type === 'weekly') {
            while ($current <= $end) {
                if (in_array(strtolower($current->format('D')), $request->days)) {
                    $dates[] = $current->copy();
                }
                $current->addDay();
            }
        } elseif ($request->recurring_type === 'monthly') {
            $dates = $this->generateMonthlyDates($request, $current, $end);
        }

        return $dates;
    }

    private function generateMonthlyDates(Request $request, Carbon $current, Carbon $end): array
    {
        $dates = [];

        if ($request->monthly_date) {
            $current = Carbon::parse($request->start_date)->startOfMonth();
            while ($current <= $end) {
                $day = min($request->monthly_date, $current->daysInMonth);
                $date = $current->copy()->day($day);

                if ($date >= Carbon::parse($request->start_date) && $date <= $end) {
                    $dates[] = $date;
                }
                $current->addMonth();
            }
        } elseif ($request->week && $request->day) {
            $current = Carbon::parse($request->start_date)->startOfMonth();
            while ($current <= $end) {
                $date = $current->copy()->modify("{$request->week} {$request->day}");
                if ($date >= Carbon::parse($request->start_date) && $date <= $end) {
                    $dates[] = $date;
                }
                $current->addMonth();
            }
        }

        return $dates;
    }

    private function generateBookingSlots(Booking $booking, array $dates, Request $request): array
    {
        $startTime = Carbon::parse($request->time);
        $endTime = $startTime->copy()->addMinutes($request->duration);

        $slots = [];
        foreach (collect($dates)->unique()->values() as $date) {
            $slots[] = [
                'booking_id' => $booking->id,
                'date' => $date->format('Y-m-d'),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => $request->duration,
                'status' => 'pending',
                'price' => $request->price,
            ];
        }

        return $slots;
    }

    private function processBookingNotifications(Booking $booking, array $slots, Address $address): array
    {
        $firebase = new FirebaseService();
        $totalSlots = count($slots);
        $availableSlots = 0;

        foreach ($slots as $slot) {
            $experts = $this->getExperts($slot['date'], $slot['start_time'], $slot['end_time'], $address->address_lat, $address->address_long);
            $slotStatus = $experts->isEmpty() ? 'not_available' : 'available';

            BookingSlot::where('booking_id', $booking->id)
                ->where('date', $slot['date'])
                ->update(['status' => $slotStatus]);

            if ($experts->isEmpty()) {
                continue;
            }

            $availableSlots++;
            $this->sendNotificationsToExperts($firebase, $experts, $booking, $slot, $address);
        }

        $message = match (true) {
            $availableSlots === 0 => 'Booking created but no experts available right now',
            $availableSlots === $totalSlots => 'Booking created & notifications sent for all slots',
            default => 'Booking created & notifications sent for some slots'
        };

        return ['message' => $message];
    }

    private function sendNotificationsToExperts(FirebaseService $firebase, $experts, Booking $booking, array $slot, Address $address): void
    {
        foreach ($experts as $expert) {
            foreach ($expert->devices as $device) {
                if (empty($device->fcm_token)) {
                    continue;
                }

                $firebase->sendNotification(
                    $device->fcm_token,
                    'New Booking Request',
                    "Booking on {$slot['date']} at {$slot['start_time']} is available for you. Please respond to accept or reject.",
                    [
                        'booking_id' => (string) $booking->id,
                        'booking_code' => $booking->booking_code,
                        'date' => $slot['date'],
                        'time' => $slot['start_time'],
                        'duration' => $slot['duration'],
                        'location' => $address->address,
                        'earning' => $slot['price'] ?? 0,
                        'actions' => json_encode([
                            ['id' => 'ACCEPT', 'title' => 'Accept'],
                            ['id' => 'REJECT', 'title' => 'Reject']
                        ])
                    ],
                );
            }
        }
    }

    //  Get nearby + free experts
    private function getExperts($date, $startTime, $endTime, $lat, $lng)
    {
        $radiusKm = 1;
        return User::where('users.role', 'expert')
            ->where('users.status', 1)
            ->join('addresses', 'addresses.user_id', '=', 'users.id')
            ->join('expert_details', 'expert_details.user_id', '=', 'users.id')
            ->where('expert_details.is_online', true)
            ->with('devices')
            ->select('users.*')
            ->selectRaw(
                "(6371 * acos(
                        cos(radians(?)) *
                        cos(radians(addresses.address_lat)) *
                        cos(radians(addresses.address_long) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(addresses.address_lat))
                    )) AS distance",
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance', 'asc')
            ->whereDoesntHave('expertSlots', function ($q) use ($date, $startTime, $endTime, ) {
                $q->where('date', $date)
                    ->where('status', 'accepted')
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);
            })
            ->get();
    }

    // booking by id detail

    public function getBookingById($id)
    {
        $booking = Booking::with([
            'service',
            'address',
            'slots.expert'
        ])->find($id);
        if (!$booking) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Booking not found',
                'data' => (object) []
            ], 422);
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Booking retrieved successfully',
            'data' => new BookingResource($booking)
        ]);
    }
    // get auth user bookings
    public function getUserBookings(Request $request)
    {
        $query = Booking::with([
            'service',
            'address',
            'slots.expert'
        ])->where('user_id', auth()->id());
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $bookings = $query->latest()->get();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => $bookings->isEmpty()
                ? 'No bookings found for this user'
                : 'User bookings retrieved successfully',
            'data' => BookingResource::collection($bookings),
            // 'pagination' => [
            //     'current_page' => $bookings->currentPage(),
            //     'last_page' => $bookings->lastPage(),
            //     'per_page' => $bookings->perPage(),
            //     'total' => $bookings->total(),
            // ]
        ]);
    }



}

