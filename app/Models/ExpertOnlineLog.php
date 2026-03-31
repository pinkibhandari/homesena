<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertOnlineLog extends Model
{
    protected $fillable = [
        'user_id',
        'online_at',	
        'offline_at'
    ];
}
