<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDevice;
// use App\Models\ExpertDetail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
// use App\Http\Requests\Auth\SendOtpRequest;
// use App\Http\Requests\Auth\VerifyOtpRequest;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    // send opt in mob.and create new user

    // public function sendOtp(SendOtpRequest $request)
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
            'role' => 'required|in:user,expert,admin',
            'deviceId' => 'required|string',
            'deviceType' => 'required|in:android,ios'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }

        $existingUser = User::where('phone', $request->phone)->first();

        // Block if phone registered with different role
        if ($existingUser && $existingUser->role !== $request->role) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'This phone number is already registered with another role',
                'data' => (object) []
            ], 422);
        }
        if ($existingUser && $existingUser->otp_expires_at > now()->subMinute()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Please wait before requesting OTP again',
                'data' => (object) []
            ], 422);
        }

        $otp = rand(100000, 999999);
        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp' => Hash::make($otp),
                'otp_expires_at' => Carbon::now()->addMinutes(10),
                'role' => $request->role
            ]
        );

        $userDevice = UserDevice::updateOrCreate(
            ['device_id' => $request->deviceId],
            [
                'user_id' => $user->id,
                'device_type' => $request->deviceType
            ]
        );

        // Expert first time registration
        if ($user->role === 'expert' && $user->wasRecentlyCreated) {

            $user->update([
                'status' => 'INACTIVE'
            ]);

            $user->expertDetail()->firstOrCreate(
                ['user_id' => $user->id],
                ['approval_status' => 'pending']
            );
        }
        $userDevice->makeHidden(['id']);
        $user->profile_image = $user->profile_image
            ? url('storage/' . $user->profile_image)
            : null;

        $all = collect($user)->merge($userDevice);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'OTP sent successfully',
            'data' => array_merge($all->toArray(), [
                'otp' => $otp
            ])
        ], 200);
    }

    // Verify OTP
    // public function verifyOtp(VerifyOtpRequest $request) 
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
            'otp' => 'required|digits:6',
            'role' => 'required|in:user,expert,admin',
            'deviceId' => 'required|string',
            'deviceType' => 'required|in:android,ios,web'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }

        $user = User::where('phone', $request->phone)
            ->where('role', $request->role)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Invalid user',
                'data' => (object) []
            ], 422);
        }

        // Check OTP expiry
        if (!$user->otp_expires_at || $user->otp_expires_at->isPast()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'OTP expired',
                'data' => (object) []
            ], 422);
        }

        // Verify OTP
        if (!Hash::check($request->otp, $user->otp)) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Invalid OTP',
                'data' => (object) []
            ], 422);
        }

        // Clear OTP
        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);

        // Create Sanctum Token
        $tokenResult = $user->createToken('mobile-token');
        $token = $tokenResult->plainTextToken;

        // Save device
        UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_id' => $request->deviceId
            ],
            [
                'device_type' => $request->deviceType,
                'token_id' => $tokenResult->accessToken->id
            ]
        );

        $user->profile_image = $user->profile_image
            ? url('storage/' . $user->profile_image)
            : null;

        $data = collect($user)->merge([
            'token' => $token
        ]);

        return response()->json([
            'code' => 200,
            'status' => true,
            'token_type' => 'Bearer',
            'data' => $data,
            'message' => 'OTP verified successfully'
        ], 200);
    }


    //  Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => (object) [],
            'message' => 'Logged out successfully'
        ]);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        // Delete tokens (logout from all devices)
        $user->tokens()->delete();
        // Soft delete user
        $user->delete();
        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => (object) [],
            'message' => 'Account deleted successfully'
        ]);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|digits:10|regex:/^[6-9]\d{9}$/',
            'role' => 'required|in:user,expert,admin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }

        $user = User::where('phone', $request->phone)
            ->where('role', $request->role)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'User not found',
                'data' => (object) []
            ], 422);
        }

        // Prevent OTP spam (allow resend after 60 seconds)
        if ($user->otp_expires_at && now()->diffInSeconds($user->otp_expires_at, false) > 540) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Please wait before requesting another OTP',
                'data' => (object) []
            ], 422);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);

        $user->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => Carbon::now()->addMinutes(10)
        ]);

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'OTP resent successfully',
            'data' =>  $otp 
        ]);
    }

}
