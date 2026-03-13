<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
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
