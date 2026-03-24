<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
