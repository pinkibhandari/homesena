<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    // ================= LIST + SEARCH =================
    public function index(Request $request)
    {
        $services = Service::query()

            //  Search (Grouped - FIXED)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('description', 'like', '%' . $request->search . '%')
                          ->orWhere('slider_title', 'like', '%' . $request->search . '%')
                          ->orWhere('slider_description', 'like', '%' . $request->search . '%');
                });
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.services.form', [
            'service' => new Service()
        ]);
    }

    // ================= EDIT =================
    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        //  Main Image Upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        //  Slider Image Upload
        if ($request->hasFile('slider_image')) {
            $data['slider_image'] = $request->file('slider_image')->store('services/slider', 'public');
        }

        Service::create($data);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service created successfully');
    }

    // ================= UPDATE =================
    public function update(Request $request, Service $service)
    {
        if ($request->has('status') && !$request->has('name')) {

            $service->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        // ✅ Normal Update
        $data = $this->validateData($request);

        //  Replace Main Image
        if ($request->hasFile('image')) {

            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
            }

            $data['image'] = $request->file('image')->store('services', 'public');
        }

        //  Replace Slider Image
        if ($request->hasFile('slider_image')) {

            if ($service->slider_image && Storage::disk('public')->exists($service->slider_image)) {
                Storage::disk('public')->delete($service->slider_image);
            }

            $data['slider_image'] = $request->file('slider_image')->store('services/slider', 'public');
        }

        $service->update($data);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service updated successfully');
    }

    // ================= DELETE =================
    public function destroy(Service $service)
    {
        //  Delete Images
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
        }

        if ($service->slider_image && Storage::disk('public')->exists($service->slider_image)) {
            Storage::disk('public')->delete($service->slider_image);
        }

        $service->delete();

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service deleted successfully');
    }

    // ================= VALIDATION =================
    private function validateData(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',

            'status' => 'required|in:0,1',

            'price' => 'nullable|numeric',
            'discount_price' => 'nullable|numeric',

            'slider_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
            'slider_title' => 'nullable|string|max:255',
            'slider_description' => 'nullable|string',

            'includes' => 'nullable|string',
            'does_not_include' => 'nullable|string',
        ]);
    }
}