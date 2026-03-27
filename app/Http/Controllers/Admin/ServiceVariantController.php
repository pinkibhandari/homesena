<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceVariant;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceVariantController extends Controller
{
    // LIST + SEARCH
    public function index(Request $request)
    {
        $variants = ServiceVariant::with('service')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->whereHas('service', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhere('duration_minutes', 'like', "%{$search}%")
                ->orWhere('base_price', 'like', "%{$search}%")
                ->orWhere('tax_percentage', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.service_variants.index', compact('variants'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.service_variants.form', [
            'variant'  => new ServiceVariant(),
            'services' => Service::pluck('name', 'id')
        ]);
    }

    // EDIT PAGE
    public function edit(ServiceVariant $service_variant)
    {
        return view('admin.service_variants.form', [
            'variant'  => $service_variant,
            'services' => Service::pluck('name', 'id')
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        ServiceVariant::create($data);

        return redirect()->route('admin.service_variants.index')
            ->with('success', 'Service Variant Created Successfully');
    }

    // UPDATE
    public function update(Request $request, ServiceVariant $service_variant)
    {
        $data = $this->validateData($request);

        $service_variant->update($data);

        return redirect()->route('admin.service_variants.index')
            ->with('success', 'Service Variant Updated Successfully');
    }

    // DELETE
    public function destroy(ServiceVariant $service_variant)
    {
        $service_variant->delete();

        return redirect()->route('admin.service_variants.index')
            ->with('success', 'Service Variant Deleted Successfully');
    }

    // VALIDATION
    private function validateData(Request $request)
    {
        return $request->validate([
            'service_id'        => 'required|exists:services,id',
            'duration_minutes'  => 'required|numeric|min:1',
            'base_price'        => 'required|numeric|min:0',
             'discount_price' => 'nullable|numeric',
            'tax_percentage'    => 'nullable|numeric|min:0|max:100',
            'is_active'         => 'required|boolean',
        ]);
    }
}