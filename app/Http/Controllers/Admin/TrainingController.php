<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingCenter;

class TrainingController extends Controller
{
    // ================= LIST + SEARCH =================
    public function index(Request $request)
    {
        $centers = TrainingCenter::query()

            // 🔍 Search (grouped)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('city', 'like', '%' . $request->search . '%')
                          ->orWhere('address', 'like', '%' . $request->search . '%')
                          ->orWhere('phone', 'like', '%' . $request->search . '%'); // ✅ added
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.training_centers.index', compact('centers'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.training_centers.form', [
            'center' => new TrainingCenter()
        ]);
    }

    // ================= EDIT =================
    public function edit(TrainingCenter $training_center)
    {
        return view('admin.training_centers.form', [
            'center' => $training_center
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        TrainingCenter::create($data);

        return redirect()
            ->route('admin.training_centers.index')
            ->with('success', 'Training center created successfully');
    }

    // ================= UPDATE =================
    public function update(Request $request, TrainingCenter $training_center)
    {
        // 🔥 AJAX STATUS TOGGLE
        if ($request->has('status') && !$request->has('name')) {

            $training_center->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        // ✅ Normal update
        $data = $this->validateData($request);

        $training_center->update($data);

        return redirect()
            ->route('admin.training_centers.index')
            ->with('success', 'Training center updated successfully');
    }

    // ================= DELETE =================
    public function destroy(TrainingCenter $training_center)
    {
        $training_center->delete();

        return redirect()
            ->route('admin.training_centers.index')
            ->with('success', 'Training center deleted successfully');
    }

    // ================= VALIDATION =================
    private function validateData(Request $request)
    {
        return $request->validate([
            'name'    => 'required|string|max:255',
            'city'    => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'phone'   => 'required|string|max:15', // ✅ added
            'status'  => 'required|in:0,1',
        ]);
    }
}