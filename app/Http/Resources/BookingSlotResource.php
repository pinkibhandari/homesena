<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'duration' => $this->duration,
            'otp_code' => $this->otp_code,
            'status' => $this->status,
            'price' => $this->price,
            'is_rescheduled' => $this->is_rescheduled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // Optional relation
            // 'booking' => new BookingResource($this->whenLoaded('booking')),
        ];
    }
}
