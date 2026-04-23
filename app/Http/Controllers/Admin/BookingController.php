<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{

    public function index(Request $request)
    {
        $bookings = Booking::with('service', 'address', 'slots.expert')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($query) use ($search) {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhere('booking_subtype', 'like', "%{$search}%")
                        ->orWhere('start_date', 'like', "%{$search}%")
                        ->orWhere('end_date', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%");
                });
            })
            //  Type
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type', $request->type);
            })

            //  Sub Type
            ->when($request->filled('sub_type'), function ($q) use ($request) {
                $q->where('booking_subtype', $request->sub_type);
            })

            //  Status
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }


    public function create()
    {
        return view('admin.bookings.create');
    }
    public function edit()
    {
        return view('admin.bookings.edit');
    }

    public function show(Booking $booking)
    {
        $booking->load(['service', 'address']);
        $slots = $booking->slots()
            ->with('expert')
            ->paginate(10);
        return view('admin.bookings.show', compact('booking', 'slots'));
    }
}
