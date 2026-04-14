<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertSupportOption extends Model
{
    protected $fillable = [
            'title',
            'type',
            'value',
            'status'
    ];
}
