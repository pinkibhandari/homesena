<?php

namespace App\Http\Controllers\Api\Expert;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertEmergencyContact;
use App\Models\ExpertDetail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ExpertEmergencyContactResource;
class EmergencyContactController extends Controller
{
    public function storeEmergencyContacts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contacts' => 'required|array|min:1',
            'contacts.*.name' => 'required',
            'contacts.*.phone' => 'required|regex:/^[6-9]\d{9}$/|distinct'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => $validator->errors()->first(),
                'data' => []
            ], 422);
        }
        $expert = ExpertDetail::where('user_id', auth()->id())->first();
        if (!$expert) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Expert not found',
                'data' => []
            ], 422);
        }
        //  Get all phones from request
        $phones = collect($request->contacts)->pluck('phone');

        // Check existing phones in DB (single query)
        $existingPhones = ExpertEmergencyContact::where('expert_detail_id', $expert->id)
            ->whereIn('phone', $phones)
            ->pluck('phone')
            ->toArray();

        if (!empty($existingPhones)) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'These numbers already exist: ' . implode(', ', $existingPhones),
                'data' => []
            ], 422);
        }

        // Prepare bulk insert
        $data = [];
        foreach ($request->contacts as $contact) {
            $data[] = [
                'expert_detail_id' => $expert->id,
                'name' => $contact['name'],
                'phone' => $contact['phone'],
            ];
        }
        ExpertEmergencyContact::insert($data);
        // Fetch saved data
        $contacts = ExpertEmergencyContact::where('expert_detail_id', $expert->id)->latest()->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Emergency contacts saved successfully',
            'data' => ExpertEmergencyContactResource::collection($contacts)
        ], 200);
    }

    public function getEmergencyContacts()
    {
        $expert = ExpertDetail::where('user_id', auth()->id())->first();
        if (!$expert) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Expert not found',
                'data' => []
            ], 422);
        }
        $contacts = ExpertEmergencyContact::where('expert_detail_id', $expert->id)->get();
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => $contacts->isEmpty()
                ? 'No emergency contacts found'
                : 'Emergency contacts retrieved successfully',
            'data' => ExpertEmergencyContactResource::collection($contacts)
        ], 200);

    }
    public function deleteEmergencyContact($id)
    {
        $expert = ExpertDetail::where('user_id', auth()->id())->first();
        if (!$expert) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Expert not found',
                'data' => (object) []
            ], 422);
        }
        $contact = ExpertEmergencyContact::where('id', $id)
                ->where('expert_detail_id', $expert->id)
                ->first();
        if (!$contact) {
            return response()->json([
                'status' => false,
                'code' => 422,
                'message' => 'Emergency contact not found',
                'data' => (object) []
            ], 422);
        }
        $contact->delete();
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => 'Emergency contact deleted successfully',
            'data' => (object) []
        ]);
    }
}
