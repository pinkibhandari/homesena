<?php

namespace App\Http\Controllers\Api\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ExpertBookingResource;
use App\Http\Resources\ExpertBookingSlotResource;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\UserDevice;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\BookingSlotLog;
use App\Http\Resources\BookingSlotResource;
use App\Models\ExpertBookingRejectReason;
class BookingController extends Controller
{
    public function bookingList(Request $request)
    {
        $expert = $request->user();
        $bookings = Booking::whereHas('slots', function ($q) use ($expert) {
            $q->where('expert_id', $expert->id);
        })
            ->with([
                'user:id,name,phone',
                'service:id,name',
                'slots' => function ($q) use ($expert) {
                    $q->where('expert_id', $expert->id);
                }
            ])
            ->latest()
            ->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $bookings->isEmpty()
                ? 'No bookings found'
                : 'Booking list retrieved successfully',
            'data' => ExpertBookingResource::collection($bookings)
        ], 200);
    }

    public function bookingDetails(Request $request, $bookingId)
    {
        $expert = $request->user();
        $booking = Booking::where('id', $bookingId)
            ->whereHas('slots', function ($q) use ($expert) {
                $q->where('expert_id', $expert->id);
            })
            ->with([
                'user:id,name,phone',
                'service:id,name',
                'slots' => function ($q) use ($expert) {
                    $q->where('expert_id', $expert->id);
                }
            ])
            ->first();
        if (!$booking) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Booking not found',
                'data' => (object) []
            ], 422);
        }

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Booking details retrieved successfully',
            'data' => new ExpertBookingResource($booking)
        ], 200);


    }

    public function upcomingBooking(Request $request)
    {
        $expert = $request->user();
        $slot = $expert->expertSlots()
            ->with([
                'booking.service:id,name',
                'booking.user:id,name,phone',
                'booking.address:id,address_line'
            ])
            ->where('status', 'accepted')
            ->whereDate('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('start_time')
            ->first();
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $slot
                ? 'Upcoming booking found'
                : 'No upcoming booking',
            'data' => $slot ? new ExpertBookingSlotResource($slot) : (object) []
        ], 200);
    }

    //  ACCEPT BOOKING
    public function acceptSlot(Request $request, FirebaseService $firebase)
    {
        $validator = Validator::make($request->all(), [
            'booking_slot_id' => 'required|exists:booking_slots,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }
        try {
            $slot = BookingSlot::findOrFail($request->booking_slot_id);
            DB::transaction(function () use ($slot, $request) {
                //  Check if already accepted
                $alreadyAccepted = BookingSlot::where('booking_id', $slot->booking_id)
                    ->where('status', 'accepted')
                    ->lockForUpdate()
                    ->exists();
                if ($alreadyAccepted) {
                    throw new \Exception('Booking already accepted by another expert');
                }
                //  Security check
                $expert = auth()->user();
                if ($slot->expert_id != $expert->id) {
                    throw new \Exception('Unauthorized');
                }
                //  Slot already processed
                if ($slot->status !== 'confirmed') {
                    throw new \Exception('Slot already processed');
                }
                //  Accept slot
                $slot->status = 'accepted';
                $slot->save();
                // Add Booking Log
                BookingSlotLog::create([
                    'booking_slot_id' => $request->booking_slot_id,
                    'expert_id' => $expert->id,
                    'action' => 'accepted',
                ]);
            });
            // Load relation instead of extra query
            $slot->load('booking');
            //  Notify user
            if (!empty($slot->booking?->user_id)) {
                $this->sendToUserDevices(
                    $slot->booking->user_id,
                    "Booking Accepted",
                    "Your booking has been accepted by expert",
                    ['type' => 'booking_accept'],
                    $firebase
                );
            }

            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'Booking accepted successfully',
                'data' => new BookingSlotResource($slot)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $e->getMessage(),
                'data' => (object) []
            ], 422);
        }
    }


    //  REJECT BOOKING

    public function rejectSlot(Request $request)
    {
        $request->validate([
            'booking_slot_id' => 'required|exists:booking_slots,id',
            'reason' => 'nullable|string'
        ]);
        $expert = auth()->user();
        $slot = BookingSlot::find($request->booking_slot_id);
        if (!$slot) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Booking slot not found',
                'data' => (object) []
            ]);
        }
        //  Security check
        if ($slot->expert_id != $expert->id) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Unauthorized',
                'data' => (object) []
            ], 422);
        }
        //  Get latest action for this expert + slot
        $latestAction = BookingSlotLog::where('booking_slot_id', $slot->id)
            ->where('expert_id', $expert->id)
            ->latest()
            ->value('action');
        if ($latestAction === 'accepted') {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'You have already accepted this booking',
                'data' => (object) []
            ], 422);
        }
        if ($latestAction === 'rejected') {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Already rejected',
                'data' => (object) []
            ], 422);
        }
        BookingSlotLog::create([
            'booking_slot_id' => $slot->id,
            'expert_id' => $expert->id,
            'action' => 'rejected',
            'reason' => $request->reason,
        ]);
        // Load relation
        $slot->load('booking');
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Booking rejected successfully',
            'data' => (object) []
        ]);
    }

    // SEND NOTIFICATION
    private function sendToUserDevices($userId, $title, $body, $data = [], $firebase)
    {
        $tokens = UserDevice::where('user_id', $userId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->unique()
            ->toArray();

        foreach ($tokens as $token) {
            $firebase->sendNotification($token, $title, $body, $data);
        }
    }

    public function verifyOtp(Request $request, $slotId)
    {
        $request->validate([
            'otp_code' => 'required|digits:6'
        ]);
        $slot = BookingSlot::with('booking:id')->find($slotId);
        if ($slot->expert_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Unauthorized',
                'data' => (object) []
            ], 422);
        }

        //  Already verified
        if ($slot->otp_verified) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'OTP already verified',
                'data' => (object) []
            ], 422);
        }

        //  Max attempts
        if ($slot->otp_attempts >= 5) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Maximum OTP attempts exceeded',
                'data' => (object) []
            ], 422);
        }
        //  Wrong OTP
        if ($slot->otp_code !== $request->otp_code) {
            $slot->increment('otp_attempts');
            $attemptsLeft = max(0, 5 - $slot->otp_attempts);
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => "Invalid OTP. Attempts left: {$attemptsLeft}",
                'data' => (object) []
            ], 422);
        }
        $startTime = now();
        $duration = $slot->duration ?? 30;
        $endTime = $startTime->copy()->addMinutes($duration);
        //  Success
        $slot->update([
            'otp_verified' => true,
            'otp_attempts' => 0,
            'status' => 'ongoing',
            'check_in_time' => now(),
            'otp_code' => null,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'OTP verified successfully',
            'data' => [
                'booking_id' => $slot->booking->id,
                'slot_id' => $slot->id,
                'status' => $slot->status,
                'check_in_time' => $slot->check_in_time?->format('Y-m-d H:i:s'),
                'start_time' => $slot->start_time?->format('H:i:s'),
                'end_time' => $slot->end_time?->format('H:i:s')
            ]
        ]);
    }

    public function bookingRejectReason()
    {
        $rejectReasonList = ExpertBookingRejectReason::select('id', 'title', 'status')
                ->where('status', 1)
               ->get();
        return response()->json([
            'code'=>200,
            'status' => true,
            'message' => 'Reject reasons fetched successfully',
            'data' => $rejectReasonList
        ]);
    }
}
