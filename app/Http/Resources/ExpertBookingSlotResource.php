<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ExpertBookingSlotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = $this->date ? Carbon::parse($this->date) : null;
        $startTime = $this->start_time ? Carbon::parse($this->start_time) : null;
        $endTime = $this->end_time ? Carbon::parse($this->end_time) : null;

         return [
            // SLOT INFO
            'slot_id' => $this->id,
            'date' => $this->date,
            'formatted_date' => $date ? $date->format('d M Y') : null,
            'is_today' => $date ? $date->isToday() : false,

            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'formatted_start_time' => $startTime ? $startTime->format('h:i A') : null,
            'formatted_end_time' => $endTime ? $endTime->format('h:i A') : null,

            'duration' => $this->duration,
            'status' => $this->status,
            'price' => $this->price,

              // BOOKING INFO
            'booking' => [
                'booking_id' => $this->booking?->id,
                'booking_code' => $this->booking?->booking_code,
                'type' => $this->booking?->type,
                'booking_subtype' => $this->booking?->booking_subtype,

                'start_date' => $this->booking?->start_date,
                'end_date' => $this->booking?->end_date,

                'time' => $this->booking?->time,
                // 'price_per_slot' => $this->booking?->price,
                'total_price' => $this->booking?->total_price,

                // 'latitude' => $this->booking?->latitude,
                // 'longitude' => $this->booking?->longitude,
                // 'address' => $this->booking?->address,
                
                // SERVICE
                'service' => [
                    'id' => $this->booking?->service?->id,
                    'name' => $this->booking?->service?->name,
                ],

                // USER
                'user' => [
                    'id' => $this->booking?->user?->id,
                    'name' => $this->booking?->user?->name,
                    'phone' => $this->booking?->user?->phone,
                ],

                //  ADDRESS 
                'address' => [
                    'id' => $this->booking?->address?->id ?? null,
                    'address' => $this->booking?->address?->address ?? null,
                    'latitude' => $this->booking?->address?->address_lat ?? null,
                    'longitude' => $this->booking?->address?->address_long ?? null,
                ],
            ],
        ];

    }
}
