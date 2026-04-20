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
    public function accept(Request $request, FirebaseService $firebase)
    {
        
        $validator = Validator::make($request->all(), [
           'booking_slot_id' => 'required|exists:booking_slots,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }
        $expert = auth()->user();
        $slot = BookingSlot::find($request->booking_slot_id);
        //  Security check
        if ($slot->expert_id != $expert->id) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Unauthorized',
                'data' => (object) []
            ], 422);
        }

        //  Already processed
        if ($slot->status != 'confirmed') {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Already processed',
                'data' => (object) []
            ], 422);
        }
        //  Accept
        $slot->status = 'accepted';
        $slot->save();
        //  Notify user
        $booking = Booking::find($slot->booking_id);
        if ($booking && $booking->user_id) {
            $this->sendToUserDevices(
                $booking->user_id,
                "Booking Accepted",
                "Your booking has been accepted by expert",
                ['type' => 'booking_accept'],
                $firebase
            );
        }
        return response()->json([
            'code'=> 200,
            'status' => true,
            'message' => 'Booking accepted successfully',
            'data'=>  $slot
        ]);
    }

     
    //  REJECT BOOKING

    public function reject(Request $request, FirebaseService $firebase)
    {
        $request->validate([
            'booking_slot_id' => 'required|exists:booking_slots,id',
            'reason' => 'nullable|string'
        ]);
        $expert = auth()->user();
        $slot = BookingSlot::find($request->booking_slot_id);
        if ($slot->expert_id != $expert->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }
        if ($slot->status != 'confirmed') {
            return response()->json([
                'status' => false,
                'message' => 'Already processed'
            ], 422);
        }
        //  Reject
        // $slot->status = 'rejected';
        // $slot->reject_reason = $request->reason;
        $slot->save();
        //  Notify user
        $booking = Booking::find($slot->booking_id);
        if ($booking && $booking->user_id) {
            $this->sendToUserDevices(
                $booking->user_id,
                "Booking Rejected",
                "Your booking has been rejected by expert",
                ['type' => 'booking_reject'],
                $firebase
            );
        }
        return response()->json([
            'status' => true,
            'message' => 'Booking rejected successfully'
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
}
