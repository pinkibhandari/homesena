<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertRatingStat extends Model
{
    protected $fillable = [
        'expert_id',
        'avg_rating',
        'total_reviews'
    ];

    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
