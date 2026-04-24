<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        //  $booking = Booking::with(['user', 'slots'])->findOrFail(3);
        // return view('invoice.booking', compact('booking'));
        try {
            // Counts
            $totalUsers = User::where('role', 'user')->count();
            $totalExperts = User::where('role', 'expert')->count();
            $totalServices = Service::count();
            $totalBookings = Booking::count();

            // Recent users (optional for table)
            $users = User::latest()->take(10)->get();

            return view('admin.dashboard.index', compact(
                'totalUsers',
                'totalExperts',
                'totalServices',
                'totalBookings',
                'users'
            ));

        } catch (\Exception $e) {

            // Error log (best practice)
            Log::error('Dashboard Error: ' . $e->getMessage());

            return view('admin.dashboard.index', [
                'totalUsers' => 0,
                'totalExperts' => 0,
                'totalServices' => 0,
                'totalBookings' => 0,
                'users' => []
            ])->with('error', 'Something went wrong');
        }
    }
}