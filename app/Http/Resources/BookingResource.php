<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
          return [
            
            'booking_id' => $this->id,
            // 'booking_created_at' => $this->booking_created_at,
            'booking_code' => $this->booking_code,
            'type' => $this->type,
            'booking_subtype' => $this->booking_subtype,
            'status' => $this->status,
            'total_amount'=>$this->total_price,
            'start_date'=>$this->start_date,
            'end_date'=>$this->end_date,
            'time'=>$this->time,
            'payment_status'=>$this->payment_status,
            // 'latitude' => $this->latitude,
            // 'longitude' => $this->longitude,
            // 'address' => $this->address,

            //  Service Details
            'service' => [
                'id' => $this->service?->id ?? null,
                'name' => $this->service?->name ?? null,
            ],

            //  Address Details
            'address' => [
                'id' => $this->address?->id ?? null,
                'address' => $this->address?->address ?? null,
                'lat' => $this->address?->address_lat ?? null,
                'lng' => $this->address?->address_long ?? null,   
            ],

            //  Multiple Slots
            'slots' =>  $this->slots ? $this->slots->map(function ($slot) {
                return [
                    'slot_id' => $slot->id,
                    'date' => $slot->date,
                    'time' => $slot->time,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'duration' => $slot->duration,
                    'status' => $slot->status,
                    'amount'=>$slot->price,
                    'payment_status'=>$slot->payment_status,

                    //  Expert per slot
                    'expert' => $slot->expert ? [
                        'id' => $slot->expert?->id,
                        'name' => $slot->expert?->name,
                        'phone' => $slot->expert?->phone,
                    ] : null,
                ];
            }): []
        ];
    }
}
