<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSupport extends Model
{
    // protected $table = 'user_supports';

   
     protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_reply',
        'resolved_at',
        'file',
        'type'
    ];
}