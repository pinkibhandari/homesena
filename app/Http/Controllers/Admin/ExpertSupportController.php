<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpertSupport;

class ExpertSupportController extends Controller
{
    /**
     * Display listing (Admin Panel)
     */
    public function index(Request $request)
    {
        $supports = ExpertSupport::with('expert')

            // 🔍 Search (name/email)
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('expert', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })

            // 🎯 Type Filter (chat/email/phone)
            ->when($request->type, function ($q) use ($request) {
                $q->where('type', $request->type);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString(); //  filters pagination me bhi maintain rahe

        return view('admin.expert_supports.index', compact('supports'));
    }

    /**
     * Delete record
     */
    public function destroy($id)
    {
        $support = ExpertSupport::findOrFail($id);
        $support->delete();

        return redirect()->route('admin.expert_supports.index')
            ->with('success', 'Support request deleted successfully');
    }
    // Show 
    public function show($id)
    {
        $support = ExpertSupport::with('expert')->findOrFail($id);

        return view('admin.expert_supports.show', compact('support'));
    }
}
