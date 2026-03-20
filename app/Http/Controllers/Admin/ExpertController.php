<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertDetail;
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
                            $q1->where('registration_code', 'like', "%{$search}%")
                                ->orWhere('onboarding_agent_code', 'like', "%{$search}%");
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
        $expert->setRelation('expertDetail', new ExpertDetail()); // 👈 important
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
                    collect($data)->only([
                        'registration_code',
                        'onboarding_agent_code',
                        'training_center_id',
                        'work_schedule',
                        'is_online'
                    ])->toArray()
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
        $data = $this->validateData($request, $expert->id);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        try {
            DB::transaction(function () use ($data, $expert) {
                    $expert->update([
                       ...$data,   // Unpack all key-value pairs of $data into this array
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
                $expertDetail = ExpertDetail::updateOrCreate(
                    ['user_id' => $expert->id],
                    collect($data)->only([
                        'registration_code',
                        'onboarding_agent_code',
                        'training_center_id',
                        'work_schedule',
                        'is_online'
                    ])->toArray()
                );
            });
            return redirect()->route('admin.experts.index')->with('success', 'Expert updated successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput() // keep old form data
                ->with('error', 'Something went wrong');
        }
    }

    // DELETE
    public function destroy(User $expert)
    {
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
            // 'password' => $id ? 'nullable|min:8' : 'required|min:8',
            'device_type' => $id ? 'nullable' : 'required|in:android,ios',
            'device_id' => $id ? 'nullable' : 'required',
            'status' => 'required|in:ACTIVE,INACTIVE',
            'registration_code' => 'required',
            'onboarding_agent_code' => 'required',
            'work_schedule' => 'required',
            'is_online' => 'required',
            'emergency_contact_name' => $id ? 'nullable' : 'required',
            'emergency_contact_phone' => $id ? 'nullable' : 'required',
            'training_center_id' => 'required',
        ]);
    }
}