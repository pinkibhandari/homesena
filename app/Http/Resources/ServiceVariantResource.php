<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $taxAmount = ($this->base_price * $this->tax_percentage) / 100;
        // $totalPrice = $this->base_price + $taxAmount;

        return [
            'id' => $this->id,
            'duration_minutes' => $this->duration_minutes,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            // 'tax_percentage' => $this->tax_percentage,
            // 'total_price' => round($totalPrice,2),
        ];
    }
}
