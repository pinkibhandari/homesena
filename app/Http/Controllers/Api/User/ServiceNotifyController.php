<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceNotifyRequest;
class ServiceNotifyController extends Controller
{



    public function storeNotifyRequest(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string',
        ]);

        $user = auth()->user();
        // prevent duplicate request (same service + location)
        $exists = ServiceNotifyRequest::where('user_id', $user->id)
            ->where('notify', 0)
            ->where('latitude', $request->latitude)
            ->where('longitude', $request->longitude)
            // ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();

        if ($exists) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Already requested. We will notify you soon.',
                'data' => (object)[]
            ]);
        }

       $notify  = ServiceNotifyRequest::create([
            'user_id' => $user->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'address' => $request->address,
        ]);

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'You will be notified when service is available nearby.',
            'data' =>  $notify
        ]);
    }
}
