<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingRejectReason;

class BookingRejectReasonController extends Controller
{
    /**
     * INDEX
     */
    public function index(Request $request)
    {
        $reasons = BookingRejectReason::query()

            //  SEARCH
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            })

            //  STATUS FILTER
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        // AJAX RESPONSE
        if ($request->ajax()) {
            return view('admin.booking_reject_reasons.index', compact('reasons'))->render();
        }

        return view('admin.booking_reject_reasons.index', compact('reasons'));
    }

    /**
     * CREATE
     */
    public function create()
    {
        return view('admin.booking_reject_reasons.form');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        BookingRejectReason::create($data);

        return redirect()->route('admin.booking_reject_reasons.index')
            ->with('success', 'Reject reason created successfully.');
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $reason = BookingRejectReason::findOrFail($id);

        return view('admin.booking_reject_reasons.form', compact('reason'));
    }

    /**
     * UPDATE
     */
   public function update(Request $request, $id)
{
    $reason = BookingRejectReason::findOrFail($id);

    // 🔥 AJAX STATUS UPDATE (FIXED LIKE USER)
    if ($request->wantsJson() && $request->has('status')) {

        $reason->status = $request->status;
        $reason->save();

        return response()->json([
            'status' => true
        ]);
    }

    // ✅ NORMAL UPDATE
    $data = $this->validateData($request);

    $reason->update($data);

    return redirect()->route('admin.booking_reject_reasons.index')
        ->with('success', 'Reject reason updated successfully.');
}

    /**
     * DELETE
     */
    public function destroy($id)
    {
        BookingRejectReason::findOrFail($id)->delete();

        return redirect()->route('admin.booking_reject_reasons.index')
            ->with('success', 'Reject reason deleted successfully.');
    }

    /**
     * VALIDATION (REUSABLE)
     */
    private function validateData($request)
    {
        return $request->validate([
            'title'  => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);
    }
}