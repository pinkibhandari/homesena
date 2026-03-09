<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
                'rating_id'    => $this->id,
                'slot_id'      => $this->booking_slot_id,
                'service_name' => $this->booking->service->name ?? null,
                'expert_name'  => $this->expert->name ?? null,
                'rating'       => $this->rating,
                'review'       => $this->review,
                'created_at'   => $this->created_at->toDateTimeString(),
            ];
        
    }
}
