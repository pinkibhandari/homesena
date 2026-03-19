<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDevice;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
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
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User created');
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
        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }
       // DELETE
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }

    // VALIDATION
    private function validateData($request, $id = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|unique:users,phone,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            // 'password' => $id ? 'nullable|min:8' : 'required|min:8',
            'device_type' => $id ? 'nullable' : 'required|in:android,ios',
            'device_id'=> $id ? 'nullable' : 'required',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ]);
    }
}
