<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ReferEarnController extends Controller
{
    //  List + Search + Only Referral Users
    public function index(Request $request)
    {
        $users = User::with(['referrer', 'referrals'])

            //  Sirf jinke paas referral_code hai
            ->whereNotNull('referred_by')
            ->where('role','user')
            ->where('referred_by', '!=', '')

            //  Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.refer_earn.index', compact('users'));
    }

    //  View Single User Referral Details

    public function show(User $refer_earn)
    {
        
        $user = $refer_earn->load(['referrer', 'referrals']);

        return view('admin.refer_earn.show', compact('user'));
    }
}