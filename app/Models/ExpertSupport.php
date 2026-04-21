<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertSupport extends Model
{
    use HasFactory;

    protected $table = 'expert_supports';

    protected $fillable = [
        'expert_id',
        'type',
        'value',
    ];

    /**
     * Relation: Expert (User)
     */
    public function expert()
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}