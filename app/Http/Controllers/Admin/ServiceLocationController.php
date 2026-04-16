<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceLocation;

class ServiceLocationController extends Controller
{
    // ================= LIST + SEARCH =================
    public function index(Request $request)
    {
        $locations = ServiceLocation::query()

            //  Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('address', 'like', "%{$request->search}%");
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.service_locations.index', compact('locations'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.service_locations.form', [
            'location' => new ServiceLocation()
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        //  Checkbox fix (same as HomePromotion)
        $data['status'] = $request->has('status') ? 1 : 0;

        ServiceLocation::create($data);

        return redirect()
            ->route('admin.service_locations.index')
            ->with('success', 'Service Location created successfully');
    }

    // ================= EDIT =================
    public function edit(ServiceLocation $service_location)
    {
        return view('admin.service_locations.form', [
            'location' => $service_location
        ]);
    }

    // ================= UPDATE =================
    public function update(Request $request, ServiceLocation $service_location)
    {
        // AJAX Status Toggle
        if ($request->has('status') && !$request->has('address')) {

            $service_location->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated'
            ]);
        }

        // ================= NORMAL UPDATE =================
        $data = $this->validateData($request);

        $data['status'] = $request->has('status') ? 1 : 0;

        $service_location->update($data);

        return redirect()
            ->route('admin.service_locations.index')
            ->with('success', 'Service Location updated successfully');
    }

    // ================= DELETE =================
    public function destroy(ServiceLocation $service_location)
    {
        $service_location->delete();

        return redirect()
            ->route('admin.service_locations.index')
            ->with('success', 'Service Location deleted successfully');
    }

    // ================= VALIDATION =================
    private function validateData($request)
    {
        return $request->validate([
            'address'   => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'status'    => 'nullable|in:0,1',
        ]);
    }
}