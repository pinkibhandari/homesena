<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Models\Booking;
use App\Services\PaymentService;
class PaymentController extends Controller
{
      protected $paymentService;
     public function __construct(PaymentService $paymentService)
          {
               $this->paymentService = $paymentService;
          }
     public function paymentMethods(){
            $methods = PaymentMethod::where('is_active', true)->get();
            if( $methods){
               return response()->json([
                         'message' => 'Payment methods fetched successfully',
                         'status' => true,
                         'data' => $methods,
                         'code'=>200
                    ],200);
            } else {
               return response()->json([
                     'code'=> 422,
                     'status'=>false,  
                     'data' => (object)[],
                     'message'=>'payment method not found'
                ],422);
            }
       }

     public function initiatePayment(Request $request){
          $request->validate([
               //    'booking_slot_id'=>'required|exists:booking_slots,id',
               'booking_id' => 'required|exists:bookings,id',
               'payment_method_id' => 'required|exists:payment_methods,id'
             ]);
          return $this->paymentService->initiatePayment($request);
     }
     public function verifyPayment(Request $request)
     {
          return $this->paymentService->verifyPayment($request);
     }

        
     
}
