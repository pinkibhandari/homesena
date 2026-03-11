<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
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
        
       if(!$user) {
            return response()->json([
                'code' => 500,
                'status' => false,
                'message' => 'Failed to send OTP'
            ], 500);
        } else {    
         return response()->json([
            'code'=> 200, 
            'status' => true,
            'message' => 'Login successfully',
            // 'message' => 'OTP sent successfully',
            'data'=> array_merge($user->toArray(), ['otp' => $otp]),
            // 'otp' => $otp 
          ],200);
        }
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
            'token_type' => 'Bearer',
            // 'token' => $token,
            'data'=> array_merge($user->toArray(), ['token' => $token]),
            'message' => 'Otp verify successfully',         
            // 'message' => 'Login successful'
        ],200);
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
         if(!$user) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'User not found'
            ], 404);
        } else {
         return response()->json([
            'code'=> 200,
            'status'=> true, 
            'message'=>'successfully', 
            'data' =>  $user
          ]);

       }
    }

    //  save auth user address
    public function saveAddress(Request $request)
    {
        $user = $request->user();
        if(!$user) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'User not found'
            ], 404);
        } else {    
             $validated = $request->validate([
                'address' => 'required|string|max:255',
                // 'flatNo' => 'required|string|max:255',
                // 'Landmark' => 'required|string|max:255',
                // 'saveAs' => 'required|string|max:255',
                // 'Pets' => 'required|string|max:255',
                // 'addressLat' => 'required|string|max:255',
                // 'addressLong' => 'required|string|max:255'
                 ]);
                $address = Address::create([
                        'user_id' => $user->id,
                        'address' => $request['address'],
                        'flat_no' => $request['flatNo'],
                        'landmark'=> $request['Landmark'],
                        'save_as'=> $request['saveAs'],
                        'pets'=> $request['Pets'],
                        'address_lat'=> $request['addressLat'],
                        'address_long'=> $request['addressLong'],                    
                    ]);
                if(!$address) {
                    return response()->json([
                        'code' => 500,
                        'status' => false,
                        'message' => 'Failed to save address'
                    ], 500);
                } else {
                return response()->json([
                    'code'=> 200,
                    'status'=> true, 
                    'message'=>'Address saved successfully', 
                    'data' =>  $address
                ]);
             }
       
         }
      }

    // auth all address list
    public function addressList(Request $request)
    {
        $user = $request->user();
        if(!$user) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'User not found'
            ], 404);
        } else {
            $addresses = $user->addresses()->get();
            return response()->json([
                'code'=> 200,
                'status'=> true, 
                'message'=>'Address list retrieved successfully', 
                'data' =>  $addresses
            ]);  
        }   
    }                                                                                                                                                                                                                                        


}
