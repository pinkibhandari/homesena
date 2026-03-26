<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\BookingRequest;
use App\Models\Booking;

class BookingControllerOld extends Controller
{
    public function store(BookingRequest $request)
        {
            $otp = rand(100000, 999999);
            $booking = $request->user()->bookings()->create([
                'service_id' => $request->serviceId,
                'address_id' => $request->addressId,
                'scheduled_at' => $request->scheduledAt,
                'duration_hours' => $request->durationHours,
                'payment_method' => $request->paymentMethod,
                'notes' => $request->notes,
                // 'total_amount' => 100.00, // For simplicity, assigning a default amount. In real scenario, you would calculate based on service and duration.
                'otp_code' => $otp,
            ]);
        if(!$booking) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Failed to create booking', 
                'data' => (object)[],
            ], 422);
        } else {
           return response()->json([
                    'code'=> 200,
                    'status'=> true, 
                    'message'=>'Booking created successfully', 
                    'data' =>  $booking
                ]);
            }
        }

        // get booking by id
    public function getBookingById($id)
    {
        // $booking = Booking::with('user', 'service', 'address')->find($id);
        $booking = Booking::with([
                    'service:id,name',
                    'expert:id,name,phone',
                    'address:id,address,address_lat,address_long'
                ])->find($id);
        if (!$booking) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Booking not found',
                'data' => (object)[],
            ], 422);
        } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Booking retrieved successfully',
                'data' => $booking
            ]);
        }
    }

    // get auth user bookings
    public function getUserBookings(Request $request)
    {     
         $query = auth()->user()
                ->bookings()
                ->join('services', 'bookings.service_id', '=', 'services.id')
                ->select('services.name as service_name',
                    'bookings.id', 
                    'bookings.booking_code', 
                    'bookings.status', 
                    'bookings.scheduled_at', 
                    'bookings.total_amount',
                    'bookings.created_at'
                    );
                

            if ($request->filled('status')) {
                $query->where('bookings.status', $request->status);
             }
           $bookings = $query->latest()->paginate(10);
           if($bookings->isEmpty()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'No bookings found for this user',
                'data' => (object)[],
            ], 422);
           } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'User bookings retrieved successfully',
                //  'data' => $bookings, 
                'data' => $bookings->items(),
                'pagination' => [
                    'page' => $bookings->currentPage(),
                    'limit' => $bookings->perPage(),
                    'totalRecords' => $bookings->total(),
                    'totalPages' => $bookings->lastPage(),
                ]
            ]);
        }         
            
      }    

      // auth user cancel booking
      public function cancelBooking(Request $request, $id)
        {
          $booking = Booking::find($id);
          if (!$booking) {
              return response()->json([
                  'code' => 422,
                  'status' => false,
                  'message' => 'Booking not found', 
                  'data' => (object)[],
              ], 422);
          }
  
          if ($booking->user_id !== auth()->id()) {
              return response()->json([
                  'code' => 422,
                  'status' => false,
                  'message' => 'Unauthorized to cancel this booking', 
                  'data' => (object)[],
              ], 422);
          }
     // only allow cancellation for bookings with status PENDING or CONFIRMED and scheduled date is after today
        if(($booking->scheduled_at->toDateString() > now()->toDateString()) && ($booking->status === 'PENDING' || $booking->status === 'CONFIRMED') ){
            $booking->status = 'CANCELLED';
            $booking->cancel_reason = $request->reason;
            $booking->save();
            if(!$booking) {
                return response()->json([
                    'code'=> 422,
                    'status'=>false,  
                    'data' => (object)[],
                    'message' => 'Failed to cancel booking'
                ], 422);
            } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Booking cancelled successfully',
                'data' => [
                    'bookingId' => $booking->id,
                    'status' => $booking->status,
                    // 'refundStatus' => 
                ]
            ]);
         }
      } else {
              return response()->json([
                   'code'=> 422, 
                    'data' => (object)[],
                   'status' => false,
                   'message' => 'Only PENDING or CONFIRMED bookings can be cancelled or scheduled date must be after today'
              ], 422);
          }
        }

    // auth user reschedule booking
        public function rescheduleBooking(Request $request, $id)
          {
            $request->validate([
                // 'newScheduledAt' => 'required|date|after:'. now()->addHour(),
                'newScheduledAt' => 'required|date|after:now',
            ]);
            $booking = Booking::find($id);
            if (!$booking) {
                return response()->json([
                    'code' => 422,
                    'status' => false,
                    'message' => 'Booking not found', 
                    'data' => (object)[],
                ], 422);
            }
    
            if ($booking->user_id !== auth()->id()) {
                return response()->json([
                    'code' => 422,
                    'status' => false,
                    'message' => 'Unauthorized to reschedule this booking',
                    'data' => (object)[],
                ], 422);
            }
            // only allow rescheduling for bookings with status PENDING or CONFIRMED and scheduled date is after today
            if($booking->status === 'PENDING' || $booking->status === 'CONFIRMED') {
                 $oldscheduledAt = $booking->scheduled_at;
                 $booking->scheduled_at = $request->newScheduledAt;
                 $booking->save();
                    if(!$booking) {
                        return response()->json([
                            'code' => 422,
                            'data' => (object)[],
                            'status' => false,
                            'message' => 'Failed to reschedule booking'
                        ], 422);
                        } else {
                        return response()->json([
                                'code' => 200,
                                'status' => true,
                                'message' => 'Booking rescheduled successfully',
                                'data' => [
                                    'bookingId' => $booking->id,
                                    'oldTime' => $oldscheduledAt,
                                    'newTime' => $booking->scheduled_at,
                                    'status' => $booking->status,
                                ]
                          ]);
                     }
            } else {
                return response()->json([
                    'code' => 422,
                    'data' => (object)[],
                    'status' => false,
                    'message' => 'Only PENDING or CONFIRMED bookings can be rescheduled'
                ], 422);
            }
        }
        
     // auth user confirm OTP
        public function confirmOtp(Request $request, $id)   
        {
            $booking = Booking::find($id);
            if (!$booking) {
                return response()->json([
                    'code' => 422,
                    'data' => (object)[],
                    'status' => false,
                    'message' => 'Booking not found'
                ], 422);
            }
    
            if ($booking->user_id !== auth()->id()) {
                return response()->json([
                    'code' => 422,
                    'data' => (object)[], 
                    'status' => false,
                    'message' => 'Unauthorized to confirm OTP for this booking'
                ], 422);
            }
            // only allow OTP confirmation for bookings with status CONFIRMED
            if ($booking->otp_code === $request->otpCode && $booking->status === 'CONFIRMED') {
                $booking->status = 'ONGOING';
                $booking->check_in_time = now()->format('Y-m-d H:i');
                $booking->save();
                    if(!$booking) {
                        return response()->json([
                             'code' => 422,
                            'data' => (object)[],
                            'status' => false,
                            'message' => 'Failed to confirm OTP'
                        ], 422);
                    } else {
                    return response()->json([
                        'code' => 200,
                        'status' => true,
                        'message' => 'OTP confirmed successfully',
                        //  'data' => $booking->only(['id','status','check_in_time'])
                         'data' => [
                                    'bookingId' => $booking->id,
                                    'checkInTime' => $booking->check_in_time,
                                    'status' => $booking->status,
                                ]
                          ]);
                 }
            } else {
                return response()->json([
                     'code' => 422,
                     'data' => (object)[],
                    'status' => false,
                    'message' => 'Invalid OTP code or booking status is not CONFIRMED'
                ], 422);
            }
        }
}
