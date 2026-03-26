<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\ExpertDetail;
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
              'phone'  => 'required| digits:10|regex:/^[6-9]\d{9}$/',
              'role' => 'required|in:user,expert,admin',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(), 
                'data' => (object)[],
            ], 422);
        } 
        $user = User::where('phone', $request->phone)->first();
        if ($user) {
        //  If role is different → block
            if ($user->role !== $request->role) {
                return response()->json([
                    'code'=>422,
                    'status' => false,
                    'data'=> (object)[],
                    'message' => 'This phone number is already registered with another role'
                ], 422);
            }
        }

        $otp = rand(100000, 999999);
        $user = User::updateOrCreate(
            ['phone' => $request->phone,
             ],
            [ 
              'otp' => Hash::make($otp),
              'otp_expires_at' => Carbon::now()->addMinutes(60),
              'role'=> $request->role ?? 'user',
            ]
           );
          $userDevice = UserDevice::updateOrCreate(
            ['device_id' => $request->deviceId],
            [
                'user_id' => $user->id,
                'device_type' => $request->deviceType,
            ]
        );
        if ($user->role === 'expert' && $user->wasRecentlyCreated) {
                  $user->update([
                       'status' => $user->role === 'expert' ? 'INACTIVE' : 'ACTIVE',
                   ]);
                    $user->expertDetail()->firstOrCreate(
                    ['user_id' => $user->id],
                    ['approval_status' => 'pending']
                );
             }
        
       $userDevice->makeHidden(['id']);
       $user->profile_image = $user->profile_image ? url('storage/' . $user->profile_image) : null;
       $all = collect($user)->merge($userDevice);  
       if(!$user) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Failed to send OTP',
                'data'=> (object)[],
            ], 422);
        } else {    
         return response()->json([
            'code'=> 200, 
            'status' => true,
             'message' => 'OTP sent successfully',
             'data'=> array_merge($all->toArray(), ['otp' => $otp]),
            // 'otp' => $otp 
          ],200);
        }
    }

   
   // Verify OTP
    // public function verifyOtp(VerifyOtpRequest $request) 
     public function verifyOtp(Request $request)
      {
            $validator = Validator::make($request->all(), [
                'phone'  => 'required| digits:10|regex:/^[6-9]\d{9}$/',
                'otp' => 'required|digits:6',
                'role' => 'required|in:user,expert,admin',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false ,
                    'code' => 422 ,
                    'message' => $validator->errors()->first(), 
                    'data' => (object)[],
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
                    'data'=> (object)[],
                ], 422);
            }
            
         if ($user->otp_expires_at < now()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Invalid or expired OTP',
                'data'=> (object)[],
            ], 422);
        }

        if (!Hash::check($request->otp, $user->otp)) {
            return response()->json([
                'status' => false ,
                'code'=> 422 ,
                'message' => 'Invalid OTP',
                'data'=> (object)[] ,
            ], 422);
        }

        $user->update([
            'otp' => null,
            'otp_expires_at' => null
        ]);
       
        // create sanctum token
        //  $token = $user->createToken('mobile-token')->plainTextToken;
         $token = $user->createToken('mobile-token');
         UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_id' => $request->deviceId
            ],
            [
                'device_type' => $request->deviceType,
                'token_id' => $token->accessToken->id
            ]
        );
        //   $profileCompleted = false;
        // if ($user->role == 'expert') {
        //     $expertDetail = ExpertDetail::where('user_id', $user->id)->first();
        //     if ($expertDetail) {
        //          $profileCompleted = true;
        //     }
        // }
        $user->profile_image = $user->profile_image ? url('storage/' . $user->profile_image) : null;
        $data = collect($user)->merge([
                'token' => $token->plainTextToken
                // 'profileCompleted' => $profileCompleted
            ]);

        return response()->json([
            'code'=> 200,
            'status' => true,
            'token_type' => 'Bearer',
            'data'=> $data,
            'message' => 'Otp verify successfully',         
        ],200);
    }


  //  Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'code'=> 200,
            'status' => true,
            'data'=> (object)[],
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
             'code'=> 200,
            'status' => true,
            'data'=> (object)[],
            'message' => 'Account deleted successfully'
        ]);
    }

  public function resendOtp(Request $request)
    {
       $validator = Validator::make($request->all(), [
                'phone'  => 'required| digits:10|regex:/^[6-9]\d{9}$/',
                'role' => 'required|in:user,expert,admin',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false ,
                    'code' => 422 ,
                    'message' => $validator->errors()->first(), 
                    'data' => (object)[],
                ], 422);
            } 

        $user = User::where('phone', $request->phone)
                ->where('role', $request->role)
               ->first();

        if (!$user) {
             return response()->json([
                    'status' => false ,
                    'code' => 422 ,
                    'message' => $validator->errors()->first(), 
                    'data' => (object)[],
                ], 422);
           }

        // Generate new OTP
         $otp = rand(100000, 999999);
        // Update OTP and time
        $user->otp = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(60);
        $user->save();
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'OTP resent successfully',
            'data' => $otp 
        ]);
    }
                                                                                                                                                                                                                                   
}
