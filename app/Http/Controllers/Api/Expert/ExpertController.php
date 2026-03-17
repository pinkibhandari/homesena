<?php

namespace App\Http\Controllers\Api\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertDetail;
use App\Models\ExpertEmergencyContact;
class ExpertController extends Controller
{
    public function storeDetails(Request $request)
    {
        $request->validate([
            'registration_code' => 'required',
            'onboarding_agent_code' => 'required',
            'training_center_id' => 'required|exists:training_centers,id',
            'work_schedule' => 'required'
        ]);
        $exists = ExpertDetail::where('user_id', auth()->id())->exists();
        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Profile already created'
            ]);
        }
        $expert = ExpertDetail::create([
            'user_id' => auth()->id(),
            'registration_code' => $request->registration_code,
            'onboarding_agent_code' => $request->onboarding_agent_code,
            'training_center_id' => $request->training_center_id,
            'work_schedule' => $request->work_schedule
        ]);
        if ($expert) {
            return response()->json([
                'status' => true,
                'message' => 'Profile created successfully',
                'data' => $expert
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Profile not created'
            ], 400);
        }
    }

    public function saveEmergencyContacts(Request $request)
    {
        $request->validate([
            'contacts' => 'required|array',
            'contacts.*.name' => 'required',
            'contacts.*.phone' => 'required|regex:/^[6-9]\d{9}$/|distinct'
        ]);
        $expert = ExpertDetail::where('user_id', auth()->id())->first();
        if (!$expert) {
            return response()->json([
                'status' => false,
                'message' => 'Expert not found'
            ]);
        }
        foreach ($request->contacts as $contact) {
            $exists = ExpertEmergencyContact::where('expert_detail_id', $expert->id)
                ->where('phone', $contact['phone'])
                ->exists();
            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone already exists: ' . $contact['phone']
                ], 422);
            }
            ExpertEmergencyContact::create([
                'expert_detail_id' => $expert->id,
                'name' => $contact['name'] ?? null,
                'phone' => $contact['phone'],
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Emergency contacts saved successfully'
        ], 200);
    }
}
