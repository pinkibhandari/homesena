<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingCenter;

class TrainingController extends Controller
{
    // LIST + SEARCH
    public function index(Request $request)
    {
        $centers = TrainingCenter::query()

            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('city', 'like', "%{$search}%")
                          ->orWhere('address', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.training_centers.index', compact('centers'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.training_centers.form', [
            'center' => new TrainingCenter()
        ]);
    }

    // EDIT PAGE
    public function edit(TrainingCenter $training_center)
    {
        return view('admin.training_centers.form', [
            'center' => $training_center
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        TrainingCenter::create($data);

        return redirect()->route('admin.training_centers.index')
                         ->with('success', 'Center created successfully');
    }

    // UPDATE
    public function update(Request $request, TrainingCenter $training_center)
    {
        $data = $this->validateData($request, $training_center->id);

        $training_center->update($data);

        return redirect()->route('admin.training_centers.index')
                         ->with('success', 'Center updated successfully');
    }

    // DELETE
    public function destroy(TrainingCenter $training_center)
    {
        $training_center->delete();

        return redirect()->route('admin.training_centers.index')
                         ->with('success', 'Center deleted successfully');
    }

    // VALIDATION
    private function validateData($request, $id = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'status' => 'required|in:1,0',
        ]);
    }
}