<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    // LIST + SEARCH
    public function index(Request $request)
    {
        $services = Service::query()
            ->when($request->filled('search'), function($q) use ($request) {
                $search = $request->search;
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slider_title', 'like', "%{$search}%")
                  ->orWhere('slider_description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.services.form', [
            'service' => new Service()
        ]);
    }

    // EDIT PAGE
    public function edit(Service $service)
    {
        return view('admin.services.form', compact('service'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Handle main image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        // Handle slider image
        if ($request->hasFile('slider_image')) {
            $data['slider_image'] = $request->file('slider_image')->store('services/slider', 'public');
        }

        Service::create($data);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service created successfully.');
    }

    // UPDATE
    public function update(Request $request, Service $service)
    {
        $data = $this->validateData($request, $service->id);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        if ($request->hasFile('slider_image')) {
            $data['slider_image'] = $request->file('slider_image')->store('services/slider', 'public');
        }

        $service->update($data);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service updated successfully.');
    }

    // DELETE
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Service deleted successfully.');
    }

    // VALIDATION
    private function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:2048',
            'status' => 'required|in:ACTIVE,INACTIVE',
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