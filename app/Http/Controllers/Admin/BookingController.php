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
            ->latest()
            ->paginate(10)
            ->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }
    //   public function getUserBookings(Request $request)
    //  { 
    //      $query  = Booking::with([
    //                     'service',
    //                     'address',
    //                     'slots.expert'
    //                     ])->where('user_id', auth()->id());
    //      if ($request->status) {
    //             $query->where('status', $request->status);
    //          }
    //     $bookings = $query->latest()->paginate(10);

    //     if($bookings->isEmpty()) {
    //         return response()->json([
    //             'code' => 422,
    //             'data'=> (object)[],
    //             'status' => false,
    //             'message' => 'No bookings found for this user'
    //         ], 422);
    //        } else {
    //         return response()->json([
    //             'code'=>200,
    //             'status' => true,
    //             'message' => 'User Bookings retrieved successfully',
    //             'data' => BookingResource::collection($bookings)
    //         ],200);
    //        }
    //   }

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
