<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertEmergencyContact extends Model
{
    protected $fillable = [
        'expert_detail_id',
        'name',
        'phone'
    ];
}
