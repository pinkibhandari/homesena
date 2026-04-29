<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceNotifyRequest;

class ServiceNotifyController extends Controller
{
    /**
     * Display listing
     */
    public function index(Request $request)
    {
        $query = ServiceNotifyRequest::with('user')->latest();

        // 🔍 SEARCH (User name / email / address)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('address', 'like', "%$search%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
            });
        }

        // 🎯 STATUS FILTER (notify)
        if ($request->notify !== null && $request->notify !== '') {
            $query->where('notify', $request->notify);
        }

        // 📄 PAGINATION
        $requests = $query->paginate(10);

        return view('admin.service_notify.index', compact('requests'));
    }

    /**
     * Delete request
     */
    public function destroy($id)
    {
        $requestItem = ServiceNotifyRequest::findOrFail($id);
        $requestItem->delete();

        return redirect()
            ->route('admin.service_notify.index')
            ->with('success', 'Service notify request deleted successfully.');
    }
    public function update(Request $request, $id)
    {
        try {
            $item = ServiceNotifyRequest::findOrFail($id);

            // 🔥 ONLY notify update (AJAX case)
            if ($request->has('notify')) {

                $request->validate([
                    'notify' => 'required|in:0,1'
                ]);

                $item->notify = $request->notify;
                $item->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Notify status updated'
                ]);
            }

            // 👇 future me agar full update use karo to yahan handle kar lena

            return response()->json([
                'status' => false,
                'message' => 'Invalid request'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }
}
