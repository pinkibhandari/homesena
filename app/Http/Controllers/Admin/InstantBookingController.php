<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InstantBookingSetting;

class InstantBookingController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $settings = InstantBookingSetting::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('duration_minutes', 'like', "%{$search}%")
                        ->orWhere('price', 'like', "%{$search}%")
                        ->orWhere('discount_price', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.instant_bookings.index', compact('settings'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $instant_booking = new InstantBookingSetting(); // important for form reuse
        return view('admin.instant_bookings.form', compact('instant_booking'));
    }

    /**
     * Store data
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        InstantBookingSetting::create($data);

        return redirect()->route('admin.instant_bookings.index')
            ->with('success', 'Instant Booking Plan Added Successfully');
    }

    /**
     * Show edit form
     */
    public function edit(InstantBookingSetting $instant_booking)
    {
        return view('admin.instant_bookings.form', compact('instant_booking'));
    }

    /**
     * Update record
     */
    public function update(Request $request, InstantBookingSetting $instant_booking)
    {
        $data = $this->validateData($request);

        $instant_booking->update($data);

        return redirect()->route('admin.instant_bookings.index')
            ->with('success', 'Instant Booking Plan Updated Successfully');
    }

    /**
     * Delete record
     */
    public function destroy(InstantBookingSetting $instant_booking)
    {
        $instant_booking->delete();

        return redirect()->route('admin.instant_bookings.index')
            ->with('success', 'Instant Booking Plan Deleted Successfully');
    }

    /**
     * Validation (Reusable)
     */
    private function validateData(Request $request)
    {
        return $request->validate([
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|lt:price',
        ]);
    }
}