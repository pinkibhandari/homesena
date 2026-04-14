<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingCancelReason;

class BookingCancelReasonController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $query = BookingCancelReason::query();

        // Optional search (future ready)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $reasons = $query->latest()->get();

        return view('admin.booking_cancel_reasons.index', compact('reasons'));
    }

    // CREATE FORM
    public function create()
    {
        return view('admin.booking_cancel_reasons.form');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        BookingCancelReason::create([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('admin.booking_cancel_reasons.index')
            ->with('success', 'Booking cancel reason created successfully.');
    }

    // EDIT FORM
    public function edit($id)
    {
        $reason = BookingCancelReason::findOrFail($id);

        return view('admin.booking_cancel_reasons.form', compact('reason'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $reason = BookingCancelReason::findOrFail($id);

        $reason->update([
            'title' => $request->title,
        ]);

        return redirect()
            ->route('admin.booking_cancel_reasons.index')
            ->with('success', 'Booking cancel reason updated successfully.');
    }

    // DELETE
    public function destroy($id)
    {
        $reason = BookingCancelReason::findOrFail($id);
        $reason->delete();

        return redirect()
            ->route('admin.booking_cancel_reasons.index')
            ->with('success', 'Booking cancel reason deleted successfully.');
    }
}