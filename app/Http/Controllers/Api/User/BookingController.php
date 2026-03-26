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

    public function createBooking(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'serviceId' => 'required',
            'serviceId' => 'required_if:type,scheduled',
            'addressId' => 'required|exists:addresses,id',
            'type' => 'required|in:instant,scheduled',
            'booking_subtype' => 'required|in:single,recurring',
            'time' => 'required',

            'date' => 'required_if:booking_subtype,single|date',

            'start_date' => 'required_if:booking_subtype,recurring|date',
            'end_date' => 'required_if:booking_subtype,recurring|date|after_or_equal:start_date',
            'recurring_type' => 'required_if:booking_subtype,recurring|in:daily,weekly,monthly',

            'days' => 'required_if:recurring_type,weekly|array',
            'monthly_date' => 'nullable|integer|min:1|max:31',
            'week' => 'nullable|integer|min:1|max:5',
            'day' => 'nullable|string',

            // 'latitude' => 'nullable',
            // 'longitude' => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => [],
            ], 422);
        }

        $existingBooking = Booking::where('user_id', auth()->id())
                    ->where('service_id', $request->serviceId)
                    ->where('type', $request->type)
                     ->where('booking_subtype', $request->booking_subtype)  
                    ->where('start_date', $request->start_date)
                    ->where('end_date', $request->end_date)
                    ->where('time', $request->time)
                    ->first();

            if($existingBooking) {
                    return response()->json([
                        'code'=>422,
                         'data' => [],
                        'status' => false,
                        'message' => 'Booking already exists for this slot and user'
                    ]);
                }
          $address = Address::find($request->addressId);
            // Check latitude & longitude
            if (!$address->address_lat || !$address->address_long) {
                return response()->json([
                    'code'=>422,
                    'data' => [],
                    'success' => false,
                    'message' => 'Selected address does not have valid location. Please update address.'
                ], 422);
            }
        
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'service_id' => $request->serviceId,
            'type' => $request->type,
            'booking_subtype' => $request->booking_subtype,
            'start_date' => $request->date ?? $request->start_date ?? now(),
            'end_date' => $request->date ?? $request->end_date ?? now(),
            'time' => $request->time,
            'status' => 'pending',
            'booking_created_at'=>now()
        ]);

        $dates = [];
        // Instant
        if ($request->type == 'instant') {
            $dates[] = now();
        }
        //  Single
        elseif ($request->booking_subtype == 'single') {
            $dates[] = Carbon::parse($request->date);
        }
        //  Recurring
        else {
            $current = Carbon::parse($request->start_date);
            $end = Carbon::parse($request->end_date);

            if ($request->recurring_type == 'daily') {
                while ($current <= $end) {
                    $dates[] = $current->copy();
                    $current->addDay();
                }
            } elseif ($request->recurring_type == 'weekly') {
                while ($current <= $end) {
                    if (in_array(strtolower($current->format('D')), $request->days)) {
                        $dates[] = $current->copy();
                    }
                    $current->addDay();
                }
            } elseif ($request->recurring_type == 'monthly') {

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
            }
        }

         $startTime = Carbon::parse($request->time);
         $endTime = $startTime->copy()->addMinutes($request->duration);

        $dates = collect($dates)->unique()->values();
        $slots = [];
        foreach ($dates as $date) {
            $slots[] = [
                'booking_id' => $booking->id,
                'date' => $date->format('Y-m-d'),
                'start_time' => $request->type == 'instant' ? now()->format('H:i:s') : $startTime,
                'end_time' => $endTime,
                'time' => $request->time,
                'duration'=> $request->duration,
                'status' => 'pending',
                // 'created_at' => now(),
                // 'updated_at' => now(),
            ];
        }

        BookingSlot::insertOrIgnore($slots);
        $latitude = $address->address_lat;
        $longitude = $address->address_long;
 
        //  SEND NOTIFICATION FOR ALL
        $firebase = new FirebaseService();
        foreach ($slots as $slot) {
            $experts = $this->getExperts( $slot['date'], $slot['start_time'], $slot['end_time'],$latitude, $longitude );
            foreach ($experts as $expert) {
                foreach ($expert->devices as $device) {
                    if ($device->firebase_token) {
                        $firebase->send(
                            $device->firebase_token,
                            'New Booking Request',
                            "Booking on {$slot['date']} at {$slot['time']}",
                            [
                                'booking_id' => (string) $booking->id,
                                'date' => $slot['date'],
                                'time' => $slot['time'],
                                'location'=>$address['address'],
                                'earning'=>$slot['amount'],
                                'actions' => json_encode([
                                        ['id' => 'ACCEPT', 'title' => 'Accept'],
                                        ['id' => 'REJECT', 'title' => 'Reject']
                                ])
                            ],
                        );
                    }
                }
            }
        }
        return response()->json([
            'status' => true,
            'code'=> 200,
            'message' => 'Booking created & notification sent',
            'data' => $slots
        ]);





    }

    //  Get nearby + free experts
    private function getExperts($date, $startTime,$endTime, $lat, $lng)
    {
        $radiusKm = 1;
        return User::where('users.role', 'expert')
            ->where('users.status', 'ACTIVE')
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
            ->whereDoesntHave('expertSlots', function ($q) use ($date, $startTime, $endTime,) {
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
                'code'=>422,
                'status' => false,
                'message' => 'Booking not found',
                'data'=> (object)[],
            ], 422);
        } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Booking retrieved successfully',
                'data'   => new BookingResource($booking)
            ],200);
        }      
    }
  // get auth user bookings
    public function getUserBookings(Request $request)
     { 
         $query  = Booking::with([
                        'service',
                        'address',
                        'slots.expert'
                        ])->where('user_id', auth()->id());
         if ($request->status) {
                $query->where('status', $request->status);
             }
        $bookings = $query->latest()->paginate(10);

        if($bookings->isEmpty()) {
            return response()->json([
                'code' => 422,
                'data'=> (object)[],
                'status' => false,
                'message' => 'No bookings found for this user'
            ], 422);
           } else {
            return response()->json([
                'code'=>200,
                'status' => true,
                'message' => 'User Bookings retrieved successfully',
                'data' => BookingResource::collection($bookings)
            ],200);
           }
      }



}

