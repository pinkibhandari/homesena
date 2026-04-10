<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpSupportContact extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'resolved_at',
        'file',
        'type'
    ];
}
