<?php

namespace App\Http\Controllers\API\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertSOS;
use App\Models\User;
use App\Models\UserDevice;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Validator;

class ExpertSOSController extends Controller
{
    public function sendSOS(Request $request, FirebaseService $firebase)
    {
         $validator = Validator::make($request->all(), [
           'latitude' => 'required',
            'longitude' => 'required',
            'booking_slot_id' => 'required|exists:booking_slots,id',
            'message' => 'nullable|string'
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
        //  Prevent spam (2 min cooldown)
        $exists = ExpertSOS::where('expert_id', $expert->id)
            ->where('created_at', '>=', now()->subMinutes(2))
            ->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Wait before sending another SOS',
                'code' => 422,
                'data' => []
            ], 422);
        }
        // Save SOS
        $sos = ExpertSOS::create([
            'expert_id' => $expert->id,
            'booking_slot_id' => $request->booking_slot_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'message' => $request->message,
            'status' => 'active',
        ]);
        $title = "🚨 SOS Alert";
        $body = "Expert {$expert->name} needs help!";
        $data = [
            'type' => 'sos',
            'sos_id' => (string) $sos->id,
            'lat' => (string) $request->latitude,
            'lng' => (string) $request->longitude,
            'map_url' => "https://maps.google.com/?q={$request->latitude},{$request->longitude}"
        ];
        //  Notify Admins
        $adminIds = User::where('role', 'admin')->pluck('id');
        foreach ($adminIds as $adminId) {
            $this->sendToUserDevices($adminId, $title, $body, $data, $firebase);
        }
        //   Notify Customer via booking_slot_id

        // $slot = BookingSlot::find($request->booking_slot_id);
        // if ($slot && $slot->booking_id) {
        //     $booking = Booking::find($slot->booking_id);
        //     if ($booking && $booking->user_id) {
        //         $this->sendToUserDevices(
        //             $booking->user_id,
        //             $title,
        //             "Your expert triggered SOS",
        //             $data
        //         );
        //     }
        // }
    }

    private function sendToUserDevices($userId, $title, $body, $data = [], $firebase)
    {
        $tokens = UserDevice::where('user_id', $userId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->unique()
            ->toArray();

        if (empty($tokens)) {
            \Log::info("No tokens for user: " . $userId);
            return;
        }
        foreach ($tokens as $token) {
            $firebase->sendNotification($token, $title, $body, $data);
        }
    }

}
