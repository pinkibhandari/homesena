<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //  Login only admin
      if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'admin'
            ])
        ) {
            $request->session()->regenerate();
            //  Redirect to dashboard
            return redirect()->route('admin.dashboard');
        }
        return back()->with('error', 'Invalid admin credentials');

    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // ================= PROFILE UPDATE =================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        // Validation (2MB limit + custom message)
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'image.required' => 'Please select an image',
            'image.image' => 'File must be an image',
            'image.mimes' => 'Only JPG, JPEG, PNG allowed',
            'image.max' => 'Image size should not exceed 2MB'
        ]);

        try {
            if ($request->hasFile('image')) {
                // Delete old image
                if (!empty($user->profile_image) && Storage::disk('public')->exists($user->profile_image)) {
                    Storage::disk('public')->delete($user->profile_image);
                }
                // Store new image (unique name auto)
                $path = $request->file('image')->store('users', 'public');
                // Save in DB
                $user->profile_image = $path;
                $user->save();
            }
            return back()->with('success', 'Profile image updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong while uploading image');
        }
    }



}
