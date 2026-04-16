<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferEarnSetting;

class ReferEarnSettingController extends Controller
{
    // LIST PAGE
    public function index()
    {
        $settings = ReferEarnSetting::latest()->get();
        return view('admin.refer_earn_settings.index', compact('settings'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.refer_earn_settings.form');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'referral_amount' => 'required|numeric',
            'signup_bonus' => 'required|numeric',
        ]);

        ReferEarnSetting::create([
            'referral_amount' => $request->referral_amount,
            'signup_bonus' => $request->signup_bonus,
        ]);

        return redirect()->route('admin.refer_earn_settings.index')
            ->with('success', 'Settings added successfully');
    }

    // EDIT PAGE
    public function edit($id)
    {
        $setting = ReferEarnSetting::findOrFail($id);
        return view('admin.refer_earn_settings.form', compact('setting'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'referral_amount' => 'required|numeric',
            'signup_bonus' => 'required|numeric',
        ]);

        $setting = ReferEarnSetting::findOrFail($id);

        $setting->update([
            'referral_amount' => $request->referral_amount,
            'signup_bonus' => $request->signup_bonus,
        ]);

        return redirect()->route('admin.refer_earn_settings.index')
            ->with('success', 'Settings updated successfully');
    }

    // DELETE (optional)
    public function destroy($id)
    {
        ReferEarnSetting::findOrFail($id)->delete();

        return redirect()->route('admin.refer_earn_settings.index')
            ->with('success', 'Deleted successfully');
    }
}
