<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;


class AuthController extends Controller
{
    // send opt in mob.and create new user

    public function sendOtp(SendOtpRequest $request)
    {
  
        $otp = rand(100000, 999999);

        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            [   'device_id'=>$request->deviceId,
                'device_type'=>$request->deviceType,
                'otp' => Hash::make($otp),
                'otp_expires_at' => Carbon::now()->addMinutes(60)
            ]
         );

        return response()->json([
            'code'=> 200, 
            'status' => true,
            'message' => 'Login successfully',
            // 'message' => 'OTP sent successfully',
            'body'=> $user,
            'otp' => $otp 
        ]);
    }

   
   // Verify OTP
    public function verifyOtp(VerifyOtpRequest $request)
      {
         $user = User::where('phone', $request->phone)->first();

         if (!$user || $user->otp_expires_at < now()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or expired OTP'
            ], 401);
        }

        if (!Hash::check($request->otp, $user->otp)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 401);
        }

        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'code'=> 200,
            'status' => true,
            'token' => $token,
            'body' => $user,
            'message' => 'Otp verify successfully',         
            // 'message' => 'Login successful'
        ]);
    }


  //  Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    // auth user details
    public function userDetails(Request $request)
    {
         $user = $request->user(); 
         return response()->json([
            'code'=> 200,
            'status'=> true, 
            'message'=>'successfully', 
            'body' =>  $user
          ]);

    }

}
