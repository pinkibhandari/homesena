<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    // ✅ LIST + SEARCH + FILTER
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'expert'])

            // 🔍 Search
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->whereHas('user', fn($q1) =>
                        $q1->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('expert', fn($q2) =>
                        $q2->where('name', 'like', "%{$search}%"))
                    ->orWhere('review', 'like', "%{$search}%");
                });
            })

            //  Rating filter
            ->when($request->filled('rating'), fn($q) =>
                $q->where('rating', $request->rating))

            //  Recommend filter
            ->when($request->filled('recommend'), fn($q) =>
                $q->where('would_recommend', $request->recommend))

            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    // ✅ EDIT
    public function edit(Review $review)
    {
        return view('admin.reviews.form', compact('review'));
    }

    // ✅ UPDATE (Form + Toggle both)
    public function update(Request $request, Review $review)
    {
        // 🔥 AJAX Toggle (Switch)
        if ($request->has('would_recommend') && !$request->has('rating')) {

            $review->update([
                'would_recommend' => $request->would_recommend
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Updated successfully'
            ]);
        }

        // ✅ Normal Form Update
        $data = $this->validateData($request);

        $review->update($data);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review updated successfully');
    }

    // ✅ DELETE
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully');
    }

    // ✅ VALIDATION
    private function validateData($request)
    {
        return $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
            'would_recommend' => 'required|in:0,1',
        ]);
    }
}