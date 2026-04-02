<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BookingSlot;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDevice;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $users = User::where('role', 'user')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // CREATE
    public function create()
    {
        return view('admin.users.form', [
            'user' => new User()
        ]);
    }

    // EDIT
    public function edit(User $user)
    {
        $user->load('devices');
        return view('admin.users.form', compact('user'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        try {
            DB::transaction(function () use ($data) {

                $user = User::create($data);

                UserDevice::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'device_id' => $data['device_id'],
                    ],
                    [
                        'device_type' => $data['device_type'],
                    ]
                );
            });

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong');
        }
    }

    // UPDATE (🔥 FIXED)
    public function update(Request $request, User $user)
{
    // ✅ AJAX STATUS UPDATE
    if ($request->wantsJson() && $request->has('status')) {

        $user->status = $request->status;
        $user->save();

        return response()->json([
            'status' => true
        ]);
    }

    // NORMAL UPDATE
    $data = $this->validateData($request, $user->id);

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return redirect()->route('admin.users.index')
        ->with('success', 'User updated successfully');
}

    // DELETE
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    // VALIDATION (🔥 CLEANED)
    private function validateData($request, $id = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|digits:10|unique:users,phone,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',

            // only required on create
            'device_type' => $id ? 'nullable' : 'required|in:android,ios',
            'device_id'   => $id ? 'nullable' : 'required',

            // 🔥 IMPORTANT
            'status' => 'required|in:0,1',
        ]);
    }

    // DELETE DEVICE
    public function deleteDevice($id)
    {
        try {
            DB::transaction(function () use ($id) {

                $device = UserDevice::findOrFail($id);

                if ($device->token_id) {
                    $deleted = PersonalAccessToken::where('id', $device->token_id)->delete();

                    if (!$deleted) {
                        throw new \Exception('Token delete failed');
                    }
                }

                if (!$device->delete()) {
                    throw new \Exception('Device delete failed');
                }
            });

            return back()->with('success', 'Device logged out successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // SHOW
    public function show($id)
    {
        $user = User::with(['bookings.service'])->findOrFail($id);

        $addresses = Address::where('user_id', $id)->latest()->get();

        $bookings = $user->bookings;

        $slots = BookingSlot::whereIn('booking_id', $bookings->pluck('id'))
            ->with(['expert', 'booking'])
            ->latest()
            ->get();

        $devices = UserDevice::where('user_id', $id)
            ->latest()
            ->get();

        return view('admin.users.show', compact(
            'user',
            'addresses',
            'bookings',
            'slots',
            'devices'
        ));
    }
}