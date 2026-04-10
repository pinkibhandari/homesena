<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        // 🔥 Slug Auto Generate (Unique)
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (Service::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $data['slug'] = $slug;
   
        //  CREATE FOLDERS IF NOT EXISTS
        $servicePath = public_path('uploads/services');
        $sliderPath  = public_path('uploads/services/slider');

        if (!file_exists($servicePath)) {
            mkdir($servicePath, 0777, true);
        }

        if (!file_exists($sliderPath)) {
            mkdir($sliderPath, 0777, true);
        }

        // Main Image Upload
        if ($request->hasFile('image')) {
            // $data['image'] = $request->file('image')->store('services', 'public');
            $file = $request->file('image');
            $filename = uniqid() . '_main.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $data['image'] = 'uploads/services/' . $filename;
        }

        // Slider Image Upload
        if ($request->hasFile('slider_image')) {
            // $data['slider_image'] = $request->file('slider_image')->store('services/slider', 'public');
            $file = $request->file('slider_image');
            $filename = uniqid() . '_slider.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services/slider'), $filename);
            $data['slider_image'] = 'uploads/services/slider/' . $filename;
        }

        Service::create($data);

        return redirect()
            ->route('admin.services.index')
            ->with('success', 'Service created successfully');
    }

    // ================= UPDATE =================
    public function update(Request $request, Service $service)
    {
        // 🔁 Status Toggle (AJAX)
        if ($request->has('status') && !$request->has('name')) {

            $service->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        //  Normal Update
        $data = $this->validateData($request);

        // 🔥 Slug Auto Update (Unique + Ignore Current ID)
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Service::where('slug', $slug)
                ->where('id', '!=', $service->id)
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        $data['slug'] = $slug;

        //  CREATE FOLDERS IF NOT EXISTS
        $servicePath = public_path('uploads/services');
        $sliderPath  = public_path('uploads/services/slider');

        if (!file_exists($servicePath)) {
            mkdir($servicePath, 0777, true);
        }

        if (!file_exists($sliderPath)) {
            mkdir($sliderPath, 0777, true);
        }

        // 🔁 Replace Main Image
        if ($request->hasFile('image')) {

            // Delete old image
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }
            // Upload new image
            $file = $request->file('image');
            $filename = uniqid() . '_main.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services'), $filename);
            $data['image'] = 'uploads/services/' . $filename;
        }

        // 🔁 Replace Slider Image
        if ($request->hasFile('slider_image')) {

            // Delete old slider image
            if ($service->slider_image && file_exists(public_path($service->slider_image))) {
                unlink(public_path($service->slider_image));
            }
            // Upload new slider image
            $file = $request->file('slider_image');
            $filename = uniqid() . '_slider.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/services/slider'), $filename);
            $data['slider_image'] = 'uploads/services/slider/' . $filename;
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
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:10240',
            'status' => 'required|in:0,1',
            'slider_image' => 'nullable|image|mimes:jpg,png,jpeg,gif,webp|max:10240',
            'slider_title' => 'nullable|string|max:255',
            'slider_description' => 'nullable|string',
            'includes' => 'nullable|string',
            'does_not_include' => 'nullable|string',
        ]);
    }
    public function show($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.services.show', compact('service'));
    }
    private function generateSlug($name, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (
            Service::where('slug', $slug)
                ->when($id, function ($query) use ($id) {
                    return $query->where('id', '!=', $id);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
