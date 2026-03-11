<?php
namespace App\Services; 

use Illuminate\Support\Facades\Http;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Razorpay\Api\Api;
use App\Models\BookingSlot;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;


class paymentService
{
    public function initiatePayment($request)
     {
        $booking = Booking::find($request->booking_id);
          if(!$booking){
               return response()->json([
                    'status'=> false,
                    'message'=> 'booking not found'
                ]);
             }
          $method = PaymentMethod::find($request->payment_method_id);
          if(!$method || !$method->is_active){
               return response()->json([
                    'status'=>false,
                    'message'=>'Payment method not available'
               ]);
            }

           switch($method->code){
                    case 'razorpay':
                         return $this->razorpayPayment($booking,$method,$request);

                    case 'stripe':
                         return $this->stripePayment($booking,$method,$request);

                    case 'cod':
                         return $this->cashPayment($booking,$method,$request);

                    case 'wallet':
                         return $this->walletPayment($booking,$method,$request);

                    default:
                         return response()->json([
                              'status'=>false,
                              'message'=>'Invalid payment method'
                         ]);
               }
     }

    private function razorpayPayment($booking, $method, $request)
      {

        $amount = $booking->total_price;

        $api = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );

        $order = $api->order->create([
            'receipt' => 'booking_'.$booking->id,
            'amount' => $amount * 100,
            'currency' => 'INR'
        ]);

         $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method_id' => $method->id,
            'gateway_order_id' => $order['id'],
            'amount' => $amount,
            'status' => 'pending',
            'currency' => 'INR' // checked
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Payment initiated successfully',
            'data' => [
                'payment_id' => $payment->id,
                'gateway' => 'razorpay',
                'gateway_order_id' => $order['id'],
                'amount' => $amount,
                'currency' => 'INR'
            ]
        ]);
    }

     private function stripePayment($booking, $method, $request)
     {

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method_id' => $method->id,
            'amount' => $booking->total_price, //need to check
            'status' => 'pending',
            'currency' => 'INR'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Stripe payment initiated',
            'data' => [
                'payment_id' => $payment->id,
                'gateway' => 'stripe',
                'amount' => $booking->total_price,
                'currency' => 'INR' // checked
            ]
        ]);
    }

    private function cashPayment($booking, $method, $request)
     {

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method_id' => $method->id,
            'amount' => $booking->total_price, 
            'status' => 'pending' ,
            'currency' => 'INR'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cash on service selected',
            'data' => [
                'payment_id' => $payment->id,
                'gateway' => 'cod',
                'amount' => $booking->total_price,
                'currency' => 'INR'
            ]
        ]);
    }



    private function walletPayment($booking,$method,$request)
    {
        $userId = Auth::id();
        $wallet = Wallet::where('user_id',$userId)->first();
        if(!$wallet){
            return response()->json([
                'status'=>false,
                'message'=>'Wallet not found'
            ]);
        }

        $amount = $request->booking_slot_id
            ? BookingSlot::find($request->booking_slot_id)->price
            : $booking->total_price;

        if($wallet->balance < $amount){
            return response()->json([
                'status'=>false,
                'message'=>'Insufficient wallet balance'
            ]);
        }

        $payment = Payment::create([
            'booking_id'=>$booking->id,
            'booking_slot_id'=>$request->booking_slot_id,
            'payment_method_id'=>$method->id,
            'amount'=>$amount,
            'status'=>'paid'
        ]);

        $wallet->balance -= $amount;
        $wallet->save();

        WalletTransaction::create([
            'user_id'=>$userId,
            'payment_id'=>$payment->id,
            'amount'=>$amount,
            'type'=>'debit',
            'description'=>'Booking payment'
        ]);

        if($request->booking_slot_id){

            BookingSlot::where('id',$request->booking_slot_id)
                ->update(['payment_status'=>'paid']);

        }else{

            BookingSlot::where('booking_id',$booking->id)
                ->update(['payment_status'=>'paid']);
        }

        return response()->json([
            'status'=>true,
            'message'=>'Payment successful using wallet',
            'data'=>[
                'payment_id'=>$payment->id,
                'gateway'=>'wallet',
                'amount'=>$amount,
                'currency'=>'INR'
            ]
        ]);
    }

    public function verifyPayment($request)
    {
        $payment = Payment::where('gateway_order_id',$request->gateway_order_id)->first();
        if(!$payment){
            return response()->json([
                'status'=>false,
                'message'=>'Payment not found'
            ]);
        }
        $payment->update([
            'gateway_payment_id'=>$request->gateway_payment_id,
            'status'=>'paid'
        ]);
        if($payment->booking_slot_id){
            BookingSlot::where('id',$payment->booking_slot_id)
                ->update(['payment_status'=>'paid']);
        }else{
            BookingSlot::where('booking_id',$payment->booking_id)
                ->update(['payment_status'=>'paid']);
        }
        return response()->json([
            'status'=>true,
            'message'=>'Payment successful'
        ]);
    }

}