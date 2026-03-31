<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertBookingResource extends JsonResource
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
            'booking_code' => $this->booking_code,
            'type' => $this->type,
            'booking_subtype' => $this->booking_subtype,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'time' => $this->time,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'payment_status' => $this->payment_status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'phone' => $this->user->phone,
                ],  
                'service' => [
                    'id' => $this->service->id,
                    'name' => $this->service->name,
                ],
        
                'booking_slots' => $this->slots ? $this->slots()->get()->map(function ($slot) {
                    return [
                        'id' => $slot->id,
                        'date' => $slot->date,
                        'time' => $slot->time,
                        'status' => $slot->status,
                        'created_at' => $slot->created_at,
                        'start_time' => $slot->start_time,
                        'end_time' => $slot->end_time,
                        'duration' => $slot->duration,
                        'price' => $slot->price,
                        'payment_status' => $slot->payment_status,
                        'check_in_time' => $slot->check_in_time
                    ];
                }):[],
        ];      
    }
}
