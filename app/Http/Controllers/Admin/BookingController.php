<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingSlot;
use App\Models\User;
use App\Models\BookingSlotLog;
use App\Models\BookingSlotNotification;

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
    public function assignExpertPage($id)
    {
        $booking = BookingSlot::findOrFail($id);

        // experts from users table
        $experts = User::where('role', 'expert')
            ->where('status', 1)
            ->get();

        return view('admin.bookings.assign-expert', compact('booking', 'experts'));
    }
    public function assignExpertSubmit(Request $request, $id)
    {
        $request->validate([
            'expert_id' => 'required|exists:users,id',
        ]);

        $booking = BookingSlot::findOrFail($id);

        $booking->expert_id = $request->expert_id;
        $booking->status = 'accepted';

        $booking->save();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Expert assigned successfully');
    }
    public function slotLogs($id)
    {
        $logs = BookingSlotLog::with('expert')
            ->where('booking_slot_id', $id)
            ->latest()
            ->get();

        return view('admin.bookings.slot_logs', compact('logs'));
    }
    public function slotNotifications($id)
{
    $notifications = BookingSlotNotification::where('booking_slot_id', $id)
        ->latest()
        ->get();

    return view('admin.bookings.slot_notifications', compact('notifications'));
}
}
