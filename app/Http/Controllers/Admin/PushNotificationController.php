<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\ServiceLocation;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    /**
     * LIST + SEARCH
     */
    // LIST + SEARCH
    public function index(Request $request)
    {
        $notifications = Notification::query()

            // SEARCH
            ->when($request->filled('search'), function ($q) use ($request) {

                $search = $request->search;

                $q->where(function ($query) use ($search) {

                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            })

            // STATUS FILTER
            ->when($request->filled('status'), function ($q) use ($request) {

                $q->where('status', $request->status);
            })

            // SEND TYPE FILTER
            ->when($request->filled('send_type'), function ($q) use ($request) {

                $q->where('send_type', $request->send_type);
            })

            // USER TYPE FILTER
            ->when($request->filled('user_type'), function ($q) use ($request) {

                $q->where('user_type', $request->user_type);
            })

            // LATEST FIRST
            ->latest()

            ->paginate(10)

            ->withQueryString();

        return view('admin.push_notifications.index', compact('notifications'));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        return view('admin.push_notifications.form', [
            'push_notification' => new Notification(),
            'locations' => ServiceLocation::where('status', 1)->get()
        ]);
    }

    /**
     * EDIT PAGE
     */
    public function edit(Notification $push_notification)
    {
        return view('admin.push_notifications.form', [
            'push_notification' => $push_notification,
            'locations' => ServiceLocation::where('status', 1)->get()
        ]);
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // If send type is ALL then remove location
        if ($data['send_type'] == 'all') {
            $data['location_id'] = null;
        }

        $data['is_sent'] = 0;

        Notification::create($data);

        return redirect()
            ->route('admin.push_notifications.index')
            ->with('success', 'Push Notification created successfully.');
    }

    /**
     * UPDATE
     */
    public function update(Request $request, Notification $push_notification)
    {
        // AJAX STATUS TOGGLE
        if ($request->has('status') && !$request->has('title')) {

            $push_notification->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Status updated successfully.'
            ]);
        }

        // NORMAL UPDATE
        $data = $this->validateData($request);

        // If send type is ALL then remove location
        if ($data['send_type'] == 'all') {
            $data['location_id'] = null;
        }

        $push_notification->update($data);

        return redirect()
            ->route('admin.push_notifications.index')
            ->with('success', 'Push Notification updated successfully.');
    }
    // SHOW
    public function show(Notification $push_notification)
    {
        return view('admin.push_notifications.show', [
            'push_notification' => $push_notification
        ]);
    }

    /**
     * DELETE
     */
    public function destroy(Notification $push_notification)
    {
        $push_notification->delete();

        return redirect()
            ->route('admin.push_notifications.index')
            ->with('success', 'Push Notification deleted successfully.');
    }

    /**
     * VALIDATION
     */
    private function validateData(Request $request)
    {
        return $request->validate([

            'title' => 'required|string|max:255',

            'message' => 'required|string',

            'send_type' => 'required|in:all,location',

            'location_id' => 'nullable|exists:service_locations,id',

            'user_type' => 'nullable|in:user,expert',

            'schedule_type' => 'nullable|in:instant,scheduled',

            'scheduled_at' => 'nullable|date',

            'status' => 'required|in:0,1',

        ]);
    }
}
