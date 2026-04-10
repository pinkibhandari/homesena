<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            // 'icon' => $this->icon ? asset('storage/'.$this->icon) : null,
            'image' => $this->image ? asset('public/'.$this->image) : null,
            'price'=>  $this->price,
            'discount_price'=> $this->discount_price,


            'variants' => ServiceVariantResource::collection(
                $this->whenLoaded('activeVariants')
            )
        ];
    }
}
