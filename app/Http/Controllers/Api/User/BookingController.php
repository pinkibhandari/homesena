<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use App\Services\ExpertSlotService;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Resources\BookingResource;
use App\Services\FirebaseService;
use App\Models\RecurringBooking;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Address;

class BookingController extends Controller
{

    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:instant,scheduled',
            'booking_subtype' => 'required_if:type,scheduled|in:single,recurring',
            'booking_date' => 'required|date',
            'slot_time' => 'required_if:type,instant,scheduled',
            'recurring_slots' => 'required_if:booking_subtype,recurring|array',
            // 'latitude' => 'required|numeric',
            // 'longitude' => 'required|numeric',
            'addressId' => 'required|exists:addresses,id',
            'serviceId' => 'required_if:type,scheduled|in:single,recurring',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) [],
            ], 422);
        }
        $user = $request->user();
        //  Create main booking
        $booking = Booking::create([
            'booking_code' => 'BK' . now()->format('md') . strtoupper(Str::random(4)),
            'user_id' => $user->id,
            'type' => $request->type,
            'booking_subtype' => $request->booking_subtype ?? 'single',
            'booking_date' => $request->booking_date,
            'slot_time' => $request->slot_time,
            'status' => 'pending'
        ]);

        $address = Address::find($request->addressId);
        // Check latitude & longitude
        if (!$address->address_lat || !$address->address_long) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'data' => (object) [],
                'message' => 'Selected address does not have valid location. Please update address.'
            ], 422);
        }

        //  Single Booking
        if ($booking->booking_subtype == 'single') {
            $this->notifyExperts($booking->booking_date, $booking->slot_time, $address->address_lat, $address->address_long, $booking, false);
        }

        // Recurring Booking
        if ($booking->booking_subtype == 'recurring') {
            foreach ($request->recurring_slots as $slot) {

                $recurring = RecurringBooking::create([
                    'booking_id' => $booking->id,
                    'slot_date' => $slot['date'],
                    'slot_time' => $slot['time'],
                    'status' => 'pending'
                ]);

                $this->notifyExperts($slot['date'], $slot['time'], $address->address_lat, $address->address_long, $recurring, true);
            }
        }

        return response()->json(['status' => true, 'message' => 'Booking created successfully']);

    }

//  Get nearby + free experts
    private function getExperts($date, $time, $lat, $lng)
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
                ->whereDoesntHave('expertBookings', function($q) use($date,$time){
                    $q->where('booking_date',$date)
                    ->where('slot_time',$time)
                    ->where('status','accepted');
                })
                ->whereDoesntHave('expertRecurringBookings', function($q) use($date,$time){
                    $q->where('slot_date',$date)
                    ->where('slot_time',$time)
                    ->where('status','accepted');
                })
              ->get();
    }

   // Notify experts
    private function notifyExperts($date, $time, $lat, $lng, $booking, $isRecurring)
    {
        $experts = $this->getExperts($date, $time, $lat, $lng);
        $allTokens = [];
        foreach($experts as $expert){
                $tokens = $expert->devices
                    ->pluck('firebase_token')
                    ->filter()
                    ->unique()
                    ->toArray();
                $allTokens = array_merge($allTokens, $tokens);
        }
          //  remove duplicates once
        $allTokens = array_unique($allTokens);

        // send ONLY ONCE
        if (!empty($allTokens)) {
            $this->sendFirebaseNotification($allTokens, $booking, $isRecurring);
        }
    }

     //  Accept booking
    public function acceptBooking($id, $isRecurring=false)
    {
        $booking = $isRecurring
            ? RecurringBooking::findOrFail($id)
            : Booking::findOrFail($id);

        if($booking->status == 'accepted'){
            return response()->json(['status'=>false,'message'=>'Already accepted']);
        }

        $booking->update([
            'status'=>'accepted',
            'expert_id'=>auth()->id()
        ]);

        return response()->json(['status'=>true,'message'=>'Accepted']);
    }

   

  // Firebase Notification
  private function sendFirebaseNotification($tokens, $booking, $isRecurring)
   {
    $serverKey = env('FIREBASE_SERVER_KEY');
    $data = [
        "registration_ids" => $tokens, //  array of tokens
        "notification" => [
            "title" => "New Booking",
            "body"  => "You have a new booking request"
        ],
        "data" => [
            "booking_id" => $booking->id,
            "type" => $isRecurring ? "recurring" : "single"
        ]
    ];

    $headers = [
        'Authorization: key=' . $serverKey,
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    curl_exec($ch);
    curl_close($ch);
  }
}

