<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpertDetailResource extends JsonResource
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
            'user_id' => $this->user_id,
            'training_center_id' => $this->training_center_id,

            // training center details
            'training_center' => [  
                'id' => $this->trainingCenter?->id,
                'name' => $this->trainingCenter?->name,
                'address' => $this->trainingCenter?->address,
                'phone' => $this->trainingCenter?->phone,
            ],
            'expert'=> [
                'id' => $this->user?->id,
                // 'name' => $this->user->name,
                'phone' => $this->user?->phone,
                // 'email' => $this->user->email,

            ] 
        ];
    }
}
