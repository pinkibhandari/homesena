<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceVariant;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ServiceVariantController extends Controller
{
    // ================= LIST + SEARCH =================
    public function index(Request $request)
    {
        $variants = ServiceVariant::with('service')

            // Search (Grouped - KEEP AS IS)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($query) use ($request) {

                    $query->whereHas('service', function ($sub) use ($request) {
                        $sub->where('name', 'like', '%' . $request->search . '%');
                    })
                        ->orWhere('duration_minutes', 'like', '%' . $request->search . '%')
                        ->orWhere('price', 'like', '%' . $request->search . '%');
                });
            })

            // Status Filter (NEW)
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('is_active', $request->status);
            })

            // Duration Filter (NEW)
            ->when($request->filled('duration'), function ($q) use ($request) {
                $q->where('duration_minutes', $request->duration);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.service_variants.index', compact('variants'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.service_variants.form', [
            'variant'  => new ServiceVariant(),
            'services' => Service::pluck('name', 'id')
        ]);
    }

    // ================= EDIT =================
    public function edit(ServiceVariant $service_variant)
    {
        return view('admin.service_variants.form', [
            'variant'  => $service_variant,
            'services' => Service::pluck('name', 'id')
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        ServiceVariant::create($data);

        return redirect()
            ->route('admin.service_variants.index')
            ->with('success', 'Service Variant Created Successfully');
    }

    // ================= UPDATE =================
    public function update(Request $request, ServiceVariant $service_variant)
    {
        //  AJAX STATUS TOGGLE
        if ($request->has('is_active') && !$request->has('service_id')) {

            $service_variant->update([
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated successfully'
            ]);
        }

        //  Normal Update
        // $data = $this->validateData($request);
        $data = $this->validateData($request, $service_variant->id);
        $service_variant->update($data);

        return redirect()
            ->route('admin.service_variants.index')
            ->with('success', 'Service Variant Updated Successfully');
    }

    // ================= DELETE =================
    public function destroy(ServiceVariant $service_variant)
    {
        $service_variant->delete();

        return redirect()
            ->route('admin.service_variants.index')
            ->with('success', 'Service Variant Deleted Successfully');
    }

    // ================= VALIDATION =================
    private function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'service_id' => 'required|exists:services,id',

            'duration_minutes' => [
                'required',
                'numeric',
                'min:1',
                Rule::unique('service_variants')
                    ->where(function ($query) use ($request) {
                        return $query->where('service_id', $request->service_id);
                    })
                    ->ignore($id),
            ],

            'price' => 'required|numeric|min:0',

            'discount_price' => 'nullable|numeric',

            'tax_percentage' => 'nullable|numeric|min:0|max:100',

            'is_active' => 'required|in:0,1',
        ], [
            'duration_minutes.unique' => 'This duration already exists for selected service.'
        ]);
    }
}
