<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertDetail;
use App\Models\ExpertRatingStat;
use App\Models\User;
use App\Models\TrainingCenter;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDevice;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;
use App\Models\ExpertEmergencyContact;

class ExpertController extends Controller
{
    // LIST + SEARCH
    public function index(Request $request)
    {
        $experts = User::with('expertDetail')
            ->where('role', 'expert')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('expertDetail', function ($q1) use ($search) {
                            $q1->where('registration_code', 'like', "%{$search}%");
                            // ->orWhere('onboarding_agent_code', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.experts.index', compact('experts'));
    }

    // CREATE
    public function create()
    {
        $expert = new User();
        $expert->setRelation('expertDetail', new ExpertDetail()); //  important
        $trainingCenters = TrainingCenter::select('id', 'name')->get();
        return view('admin.experts.form', compact('expert', 'trainingCenters'));
    }

    // EDIT
    public function edit(User $expert)
    {
        if (!$expert->expertDetail) {
            $expert->expertDetail()->create([]);
        }
        $expert->load([
            'expertDetail.trainingCenter',     // training center
            'expertDetail.emergencyContacts'   // emergency contacts
        ]);
        $trainingCenters = TrainingCenter::select('id', 'name')->get();
        return view('admin.experts.form', compact('expert', 'trainingCenters'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        try {
            DB::transaction(function () use ($data) {
                $data['role'] = 'expert';
                $user = User::create($data);
                UserDevice::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'device_id' => $data['device_id'],
                    ],
                    [
                        'device_type' => $data['device_type'] ?? null,
                    ]
                );
                $expertDetail = ExpertDetail::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'registration_code' => 'EXP-' . rand(100000, 999999),
                        'training_center_id' => $data['training_center_id'],
                        'is_online' => $data['is_online'],
                        'approval_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now()
                    ]
                );
                ExpertEmergencyContact::updateOrCreate(
                    ['expert_detail_id' => $expertDetail->id],
                    [
                        'name' => $data['emergency_contact_name'] ?? null,
                        'phone' => $data['emergency_contact_phone']  ?? null,
                    ]
                );
            });
            return redirect()->route('admin.experts.index')->with('success', 'Expert created successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput() // keep old form data
                ->with('error', 'Something went wrong');
        }
    }

    // UPDATE
    public function update(Request $request, User $expert)
    {
        //  STATUS TOGGLE (same as USER)
        if ($request->wantsJson() && $request->has('status')) {
            $expert->update([
                'status' => $request->status // 1 or 0
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully'
            ]);
        }
        //  NORMAL UPDATE
        $data = $this->validateData($request, $expert->id);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        try {
            DB::transaction(function () use ($data, $expert) {
                $expert->update([
                    ...$data,
                    'role' => 'expert'
                ]);
                UserDevice::updateOrCreate(
                    [
                        'user_id' => $expert->id,
                        'device_id' => $data['device_id'] ?? null,
                    ],
                    [
                        'device_type' => $data['device_type'] ?? null,
                    ]
                );
                ExpertDetail::updateOrCreate(
                    ['user_id' => $expert->id],
                    [
                        'training_center_id' => $data['training_center_id'],
                        'is_online' => $data['is_online'],
                    ]
                );
            });
            return redirect()->route('admin.experts.index')
                ->with('success', 'Expert updated successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong');
        }
    }
    // DELETE
    public function destroy(User $expert)
    {
        $expert->tokens()->delete(); // remove tokens
        $expert->delete();
        return redirect()->route('admin.experts.index')
            ->with('success', 'Expert deleted successfully');
    }

    // VALIDATION
    private function validateData($request, $id = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10|unique:users,phone,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'device_type' => $id ? 'nullable' : 'required|in:android,ios',
            'device_id' => $id ? 'nullable' : 'required',
            'status' => 'required|in:0,1',
            'is_online' => 'required',
            'emergency_contact_name' => $id ? 'nullable' : 'required',
            'emergency_contact_phone' => $id ? 'nullable' : 'required',
            'training_center_id' => 'required',
        ]);
    }

    public function updateApproveStatus(Request $request)
    {
        $expert = ExpertDetail::where('user_id', $request->id)->first();
        if (!$expert) {
            return response()->json([
                'status' => false,
                'message' => 'Expert not found'
            ], 422);
        }
        //  APPROVE
        if ($request->approval_status == 1) {

            $expert->update([
                'approval_status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'registration_code' => 'EXP-' . rand(100000, 999999)
            ]);
        } else {
            //  OPTIONAL: unapprove
            $expert->update([
                'approval_status' => 'pending',
                'approved_by' => null,
                'approved_at' => null
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully',
            'data' => $expert
        ]);
    }
    public function show(User $expert)
{
   $expert->load([
    'addresses',
    'ratingStat',
    'expertDetail.trainingCenter',
    'expertDetail.emergencyContacts',
    'expertSlots',
    'devices',
    'onlineLogs' // ✅ add this
]);

    return view('admin.experts.show', compact('expert'));
}
}