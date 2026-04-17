<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Booking;
use App\Http\Resources\BookingResource;
use App\Services\FirebaseService;
use App\Models\BookingSlot;

class RescheduleBookingController extends Controller
{
    public function rescheduleBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'time' => 'required',
            'start_date' => 'nullable|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'recurring_type' => 'nullable|in:daily,weekly,monthly',
            'days' => 'nullable|array',
            'days.*' => 'in:mon,tue,wed,thu,fri,sat,sun',
            'monthly_date' => 'nullable|integer|min:1|max:31',
            'week' => 'nullable|integer|min:1|max:5',
            'day' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) [],
            ], 422);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::find($request->booking_id);
            if (!$booking) {
                return response()->json([
                    'status' => false,
                    'code' => 422,
                    'message' => 'Booking not found',
                    'data' => (object) [],
                ], 422);
            }
            // Delete OLD slots ONLY
            BookingSlot::where('booking_id', $booking->id)->delete();
            // Update booking fields
            $booking->time = $request->time ?? $booking->start_date;
            $booking->start_date = $request->start_date ?? $booking->start_date;
            $booking->end_date = $request->end_date ?? $booking->end_date;
            if ($booking->booking_subtype === 'recurring') {
                 $recurringData = null;
                if ($request->recurring_type) {
                    if ($request->recurring_type === 'monthly') {
                        $recurringData = [
                            'type' => 'monthly',
                             'date' => (int) $request->monthly_date,
                        ];
                    } elseif ($request->recurring_type === 'weekly') {
                        $recurringData = [
                            'type' => 'weekly',
                            'days' => $request->days,
                        ];
                    } elseif ($request->recurring_type === 'daily') {
                        $recurringData = [
                            'type' => 'daily',
                        ];
                    }
                }
             $booking->recurring_data = $recurringData;
            }
            $booking->is_rescheduled = 1;
            $booking->save();

            //  3. Generate NEW dates (reuse your function)
            $dates = $this->generateBookingDates($request);

            //  IMPORTANT: override request values if not sent
            $request->merge([
                'start_date' => $booking->start_date,
                'end_date' => $booking->end_date,
                'recurring_type' => $booking->recurring_type,
            ]);

            //   Generate NEW slots
            $slots = $this->generateBookingSlots($booking, $dates, $request);

            // Insert slots
            BookingSlot::insert($slots);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Booking rescheduled successfully',
                'data' => new BookingResource($booking)
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

}
