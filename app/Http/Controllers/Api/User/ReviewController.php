<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ExpertRatingStat;
use App\Models\BookingSlot;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserReviewResource;
use Illuminate\Support\Facades\Validator;



class ReviewController extends Controller
{
    public function submitReview(Request $request, $slotId)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'would_recommend' => 'nullable|boolean'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => $validator->errors()->first(),
                'data' => (object) []
            ], 422);
        }
        $slot = BookingSlot::where('id', $slotId)
            ->where('status', 'completed')
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->first();
        if (!$slot) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Review allowed only for completed slots',
                'data' => (object) []
            ], 422);
        }
        $exists = Review::where('booking_slot_id', $slotId)->exists();
        if ($exists) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Review already submitted',
                'data' => (object) []
            ], 422);
        }
        try {
            $review = DB::transaction(function () use ($request, $slot) {
                $review = Review::create([
                    'expert_id' => $slot->expert_id,
                    'booking_id' => $slot->booking_id,
                    'booking_slot_id' => $slot->id,
                    'user_id' => auth()->id(),
                    'rating' => $request->rating,
                    'review' => $request->review,
                    'would_recommend' => $request->would_recommend ?? false
                ]);
                $stat = ExpertRatingStat::firstOrCreate([
                    'expert_id' => $slot->expert_id
                ], [
                    'avg_rating' => 0,
                    'total_reviews' => 0
                ]);
                $total = $stat->total_reviews;
                $avg = $stat->avg_rating;
                $newAvg = (($avg * $total) + $request->rating) / ($total + 1);
                $stat->update([
                    'avg_rating' => round($newAvg, 1),
                    'total_reviews' => $total + 1
                ]);

                return $review;
            });
            return response()->json([
                'code' => 200,
                'status' => true,
                'message' => 'Review submitted successfully',
                'data' => $review
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'code' => 422,
                'status' => false,
                'message' => 'Failed to submit review',
                'data' => (object) []
            ], 422);
        }
    }

    public function getUserGivenReviews()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with(['expert', 'booking.service'])
            ->latest()
            ->paginate(10);
        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => $reviews->total() > 0
                ? 'User reviews fetched successfully'
                : 'No reviews found for the user',
            'data' => UserReviewResource::collection($reviews),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total_records' => $reviews->total()
            ]
        ]);
    }
}
