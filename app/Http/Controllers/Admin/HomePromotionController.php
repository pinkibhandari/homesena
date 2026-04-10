<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePromotion;

class HomePromotionController extends Controller
{
    // ================= LIST + SEARCH + FILTER =================
    public function index(Request $request)
    {
        $promotions = HomePromotion::query()

            // 🔍 Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                });
            })

            // 📅 Date Filter
            ->when($request->filled('date'), function ($q) use ($request) {
                $q->whereDate('promotion_datetime', $request->date);
            })

            // 🔘 Status Filter
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.home_promotion.index', compact('promotions'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.home_promotion.form', [
            'home_promotion' => new HomePromotion()
        ]);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // ✅ Checkbox fix
        $data['status'] = $request->has('status') ? 1 : 0;

        // 📁 Create folder if not exists
        $path = public_path('uploads/home_promotion');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // 📤 Image Upload (like ServiceController)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = uniqid() . '_promotion.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/home_promotion'), $filename);

            $data['image'] = 'uploads/home_promotion/' . $filename;
        }

        HomePromotion::create($data);

        return redirect()
            ->route('admin.home_promotion.index')
            ->with('success', 'Promotion created successfully');
    }

    // ================= EDIT =================
    public function edit(HomePromotion $home_promotion)
    {
        return view('admin.home_promotion.form', compact('home_promotion'));
    }

    // ================= UPDATE =================
    public function update(Request $request, HomePromotion $home_promotion)
    {
        // 🔁 AJAX Status Toggle
        if ($request->has('status') && !$request->has('title')) {

            $home_promotion->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated'
            ]);
        }

        // ================= NORMAL UPDATE =================
        $data = $this->validateData($request);

        // Checkbox fix
        $data['status'] = $request->has('status') ? 1 : 0;

        // 📁 Ensure folder exists
        $path = public_path('uploads/home_promotion');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // 🔁 Replace Image
        if ($request->hasFile('image')) {

            // Delete old image
            if ($home_promotion->image && file_exists(public_path($home_promotion->image))) {
                unlink(public_path($home_promotion->image));
            }

            // Upload new image
            $file = $request->file('image');
            $filename = uniqid() . '_promotion.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/home_promotion'), $filename);

            $data['image'] = 'uploads/home_promotion/' . $filename;
        }

        $home_promotion->update($data);

        return redirect()
            ->route('admin.home_promotion.index')
            ->with('success', 'Promotion updated successfully');
    }

    // ================= DELETE =================
    public function destroy(HomePromotion $home_promotion)
    {
        // 🗑 Delete image
        if ($home_promotion->image && file_exists(public_path($home_promotion->image))) {
            unlink(public_path($home_promotion->image));
        }

        $home_promotion->delete();

        return redirect()
            ->route('admin.home_promotion.index')
            ->with('success', 'Promotion deleted successfully');
    }

    // ================= VALIDATION =================
    private function validateData($request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'promotion_datetime' => 'nullable|date',
            'status' => 'required|in:0,1',
        ]);
    }
}