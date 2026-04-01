<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomePromotion;

class HomePromotionController extends Controller
{
    //  LIST + SEARCH + FILTER
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
            ->when($request->filled('date'), fn($q) =>
            $q->whereDate('promotion_datetime', $request->date))

            // 🔘 Status Filter
            ->when($request->filled('status'), fn($q) =>
            $q->where('status', $request->status))

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.home_promotion.index', compact('promotions'));
    }
    // create
    public function create()
    {
        return view('admin.home_promotion.form', [
            'home_promotion' => new HomePromotion()
        ]);
    }
    // store
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // ✅ Checkbox fix (important)
        $data['status'] = $request->has('status') ? 1 : 0;

        // ✅ Image Upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('home_promotion', 'public');
        }

        HomePromotion::create($data);

        return redirect()
            ->route('admin.home_promotion.index')
            ->with('success', 'Promotion created successfully');
    }
    //  EDIT
    public function edit(HomePromotion $home_promotion)
    {
        return view('admin.home_promotion.form', compact('home_promotion'));
    }

    //  UPDATE (Form + Toggle)
    public function update(Request $request, HomePromotion $home_promotion)
    {
        // 🔥 AJAX Toggle (Status)
        if ($request->has('status') && !$request->has('title')) {

            $home_promotion->update([
                'status' => $request->status
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Status updated'
            ]);
        }

        //  Normal Form Update
        $data = $this->validateData($request);

        // Image Upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('home_promotion', 'public');
        }

        $home_promotion->update($data);

        return redirect()
            ->route('admin.home_promotion.index')
            ->with('success', 'Promotion updated successfully');
    }

    //  DELETE
    public function destroy(HomePromotion $home_promotion)
    {
        $home_promotion->delete();

        return redirect()
            ->route('admin.home_promotion.index')
            ->with('success', 'Promotion deleted successfully');
    }

    //  VALIDATION
    private function validateData($request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'promotion_datetime' => 'nullable|date',
            'status' => 'required|in:0,1',
        ]);
    }
}
