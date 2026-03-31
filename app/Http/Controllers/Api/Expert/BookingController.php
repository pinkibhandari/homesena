<?php

namespace App\Http\Controllers\Api\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ExpertBookingResource;
use App\Http\Resources\ExpertBookingSlotResource;
use App\Models\Booking;

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


}
