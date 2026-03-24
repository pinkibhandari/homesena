<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ExpertRatingStat;
use App\Models\BookingSlot;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserReviewResource;



class ReviewController extends Controller
{
    public function submitReview(Request $request, $slotId)
    {
        // Validate the incoming request data
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'review' => 'nullable|string|max:1000',
                //  'tags' => 'nullable|array',
                //  'tags.*' => 'string',
                'would_recommend' => 'nullable|boolean'
            ]);
            $slot = BookingSlot::where('id', $slotId)
                    ->where('status', 'completed')
                    ->first();

            if(!$slot) {
                return response()->json([
                    'code'=> 422,
                    'status'=>false,  
                    'data' => (object)[],
                    'message' => 'Review allowed only for completed slots'
                ], 422);
            }
            $exists = Review::where('booking_slot_id', $slotId)->exists();
            if($exists) {
                return response()->json([
                     'code'=> 422,
                    'status'=>false,  
                    'data' => (object)[],
                    'message' => 'Review already submitted'
                ], 422);
             }
     try {
         // Use a transaction to ensure data integrity
                $review = DB::transaction(function () use ($request, $slotId, $slot) {
                // Create the review
                    $review = Review::create([
                            'expert_id' => $slot->expert_id,
                            'booking_id'=> $slot->booking_id,
                            'booking_slot_id' => $slotId,
                            'user_id' => auth()->id(),
                            'rating' => $request->rating,
                            'review' => $request->review,
                            'would_recommend' => $request->would_recommend ?? false
                        ]);
                // Create the ExpertRatingStat
                       $stat = ExpertRatingStat::firstOrCreate([
                              'expert_id'=>$slot->expert_id
                           ]);
                        $total = $stat->total_reviews;
                        $avg = $stat->avg_rating;
                        $newAvg = (($avg * $total) + $request->rating) / ($total + 1); // Calculate the new average rating
                        // Update the ExpertRatingStat with the new average rating and total reviews
                        $stat->update([
                            'avg_rating'=>round($newAvg,1),
                            'total_reviews'=>$total + 1
                        ]);   

                     return $review;    
               });

            return response()->json([
                        'code'=>200,
                        'status' => 'success',
                        'message' => 'Review submitted successfully',
                        'data'=> $review
                   ], 200);       
        } catch (\Throwable $e) {
            return response()->json([
                    'code'=> 422,
                    'status'=>false,  
                    'data' => (object)[],
                     'message' => 'Failed to submit review: ' . $e->getMessage()
            ], 422);
        }

    }

    public function getUserGivenReviews()
    {
        $reviews = Review::where('user_id', auth()->id())
                    ->with(['expert', 'booking.service'])
                    ->latest()
                    ->paginate(10);

        if($reviews->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No reviews found for the user',
                'data' => [],
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'total_pages' => $reviews->lastPage(),
                    'total_records' => $reviews->total()
                ]
            ], 200);
        }

        return response()->json([
                'status' => 'success',
                'message' => 'User reviews fetched successfully',
                'data' => UserReviewResource::collection($reviews),
                'pagination' => [
                    'current_page' => $reviews->currentPage(),
                    'total_pages' => $reviews->lastPage(),
                    'total_records' => $reviews->total()
                ]
          ],200);
    }
}
