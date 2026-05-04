<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertSOS;

class ExpertSosController extends Controller
{
    // ================= LIST + SEARCH + FILTER =================
    public function index(Request $request)
    {
        $sos = ExpertSOS::with(['expert', 'bookingSlot'])

            // 🔍 Search (Expert name / message)
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('message', 'like', "%{$search}%")
                        ->orWhereHas('expert', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            })

            // 🔘 Status Filter
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.expert_sos.index', compact('sos'));
    }

    // ================= SHOW =================
    public function show($id)
    {
        $expert_sos = ExpertSOS::with(['expert', 'bookingSlot'])->findOrFail($id);

        return view('admin.expert_sos.show', compact('expert_sos'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $expert_sos = ExpertSOS::findOrFail($id);

        // ✅ Validation
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved',
        ]);

        // ✅ Update
        $expert_sos->status = $request->status;

        if ($request->status === 'resolved') {
            $expert_sos->resolved_at = now();
        } else {
            $expert_sos->resolved_at = null;
        }

        $expert_sos->save();

        // ✅ ALWAYS return JSON (important for fetch)
        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully'
        ]);
    }
    // ================= DELETE =================
  
    public function destroy($id)
    {
        $expert_sos = ExpertSOS::findOrFail($id);
        $expert_sos->delete();

        return redirect()
            ->route('admin.expert_sos.index')
            ->with('success', 'SOS deleted successfully');
    }
}
