<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSupport extends Model
{
    protected $table = 'user_supports';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',  
        'file',      
        'status',
    ];
}