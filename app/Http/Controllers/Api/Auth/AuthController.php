<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    // send opt in mob.and create new user

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

        //  Fixed user case
        if ($request->phone == config('app.fixed_phone') && $request->role == 'user') {
            return response()->json([
                'status' => true,
                'code' => 200,
                'message' => 'OTP sent successfully',
                'is_fixed' => true,
                'data' => [
                    'phone' => $request->phone,
                    'otp' => '123456',
                ]
            ]);
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
        if ($existingUser && $existingUser->otp_expires_at && $existingUser->otp_expires_at->isFuture()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Please wait before requesting OTP again',
                'data' => (object) []
            ], 422);
        }

        $otp = rand(100000, 999999);
        $status = $request->role === 'expert' ? 0 : 1;
        $user = User::updateOrCreate(
            ['phone' => $request->phone],
            [
                'otp' => Hash::make($otp),
                'otp_expires_at' => Carbon::now()->addMinutes(5),
                'role' => $request->role,
                'status' => $status
            ]
        );
        // Expert first time registration
        if ($user->role === 'expert' && $user->wasRecentlyCreated) {
            $user->expertDetail()->firstOrCreate(
                ['user_id' => $user->id],
                ['approval_status' => 'pending']
            );
        }
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'OTP sent successfully',
            'data' => array_merge($user->toArray(), [
                'otp' => $otp
            ])
        ], 200);
    }

    // Verify OTP
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

        if ($request->phone == config('app.fixed_phone') && $request->otp == config('app.fixed_otp') && $request->role == 'user') {
            $user = User::where('phone', config('app.fixed_phone'))->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Fixed user not found'
                ], 404);
            }
        } else {
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
            // generate referral code for this user
            if (!$user->referral_code) {
                $user->referral_code = $this->generateReferralCode();
            }
            // convert referral code to user id
            if ($user->referred_by) {
                $referrer = User::where('referral_code', $user->referred_by)->first();
                if ($referrer) {
                    $user->referred_by = $referrer->id;
                } else {
                    $user->referred_by = null;
                }
            }
            $user->save();
        }
        // Create Sanctum Token  (common for fixed and normal user after OTP verification)
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
            'otp_expires_at' => Carbon::now()->addMinutes(5)
        ]);

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'OTP resent successfully',
            'data' => $otp
        ]);
    }

    //  Add the referral code generator here
    private function generateReferralCode()
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (User::where('referral_code', $code)->exists());
        return $code;
    }

}
