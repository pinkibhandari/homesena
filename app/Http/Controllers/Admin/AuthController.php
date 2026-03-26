<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Storage;
class AuthController extends Controller
{
    // ================= LOGIN =================
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'admin'
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Invalid admin credentials');
    }

    // ================= LOGOUT =================
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // ================= PROFILE =================
    public function profile()
    {
        return view('admin.profile', ['user' => Auth::user()]);
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

    // ================= SHOW CHANGE PASSWORD FORM =================
    public function showChangePasswordForm()
    {
        return view('admin.auth.change_password'); // blade file
    }

    // ================= UPDATE PASSWORD =================
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Password updated successfully');
    }
    // ================= FORGOT PASSWORD PAGE =================
    public function showForgot()
    {
        return view('admin.auth.forgot_password');
    }

    // ================= SEND OTP =================
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found');
        }

        if ($user->role !== 'admin') {
            return back()->with('error', 'You are not admin');
        }

        // Generate OTP
        $otp = rand(100000, 999999);

        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        // Save email in session for OTP verification
        session(['reset_email' => $user->email]);

        return redirect()->route('admin.otp')
            ->with('success', 'OTP sent successfully. Check your email.');
    }

    // ================= SHOW OTP PAGE =================
    public function showOtp()
    {
        if (!session('reset_email')) {
            return redirect()->route('admin.forgot')
                ->with('error', 'Please enter your email first.');
        }

        return view('admin.auth.otp'); // Blade page with OTP input only
    }

    // ================= VERIFY OTP =================
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('admin.forgot')->with('error', 'Please enter your email first.');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('admin.forgot')->with('error', 'User not found.');
        }

        if ($user->otp != $request->otp) {
            return back()->with('error', 'Invalid OTP');
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            return back()->with('error', 'OTP expired');
        }

        // OTP correct → mark verified in session
        session(['otp_verified' => true]);

        return redirect()->route('admin.resetPassword');
    }

    // ================= SHOW RESET PASSWORD FORM =================
    public function showResetPassword()
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('admin.forgot')->with('error', 'Verify OTP first.');
        }

        return view('admin.auth.reset_password'); // Blade page for new password
    }

    // ================= RESET PASSWORD =================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $email = session('reset_email');
        if (!$email || !session('otp_verified')) {
            return redirect()->route('admin.forgot')->with('error', 'Verify OTP first.');
        }

        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Clear session
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('admin.login')->with('success', 'Password reset successfully. You can now login.');
    }
}
