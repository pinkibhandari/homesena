<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDevice;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
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
            // ->when($request->filled('status'), function ($q) use ($request) {
            //     $q->where('status', $request->status);
            // })

            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', [
            'user' => new User()
        ]);
    }

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
        } else {
            unset($data['password']);
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
            return redirect()->route('admin.users.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return back()
                ->withInput() // keep old form data
                ->with('error', 'Something went wrong');
        }
        // 
    }

    // UPDATE
    public function update(Request $request, User $user)
    {
        $data = $this->validateData($request, $user->id);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }
    // DELETE
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
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
        ]);
    }

    public function deleteDevice($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $device = UserDevice::findOrFail($id);
                // delete token
                if ($device->token_id) {
                    $deleted = PersonalAccessToken::where('id', $device->token_id)->delete();
                    //  if token not deleted → throw error
                    if (!$deleted) {
                        throw new \Exception('Token delete failed');
                    }
                }
                // delete device
                if (!$device->delete()) {
                    throw new \Exception('Device delete failed');
                }
            });
            return back()->with('success', 'Device logged out successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    // show
   public function show($id)
{
    $user = User::findOrFail($id);

    // User ke saare addresses fetch karo
    $addresses = Address::where('user_id', $id)->get();

    return view('admin.users.show', compact('user', 'addresses'));
}
}
