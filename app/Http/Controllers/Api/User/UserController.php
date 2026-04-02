<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{

    // auth user details
    public function userDetails(Request $request)
    {
        $user = $request->user();
        $user->profile_image = $user->profile_image ? url('storage/' . $user->profile_image) : null;
        if (!$user) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'User not found',
                'data' => (object) [],
            ], 422);
        } else {
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'successfully',
                'data' => $user
            ]);

        }
    }

   

    public function profile(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => (object) [],
            ], 422);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->profile_completed = true;
        if ($request->hasFile('profile_image')) {
            // delete old image
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // store new image
            $imagePath = $request->file('profile_image')->store('profile', 'public');
            $user->profile_image = $imagePath;
        }
        $user->save();
        $user->profile_image = $user->profile_image ? url('storage/' . $user->profile_image) : null;
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }
}
