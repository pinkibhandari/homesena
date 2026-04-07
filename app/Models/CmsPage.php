<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CmsPage extends Model
{
     protected $fillable = [
        'title',
        'slug',
        'type', 
        'content',
        'status',
        'type',
    ];

    //  protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($page) {
    //         if (empty($page->slug)) {
    //             $page->slug = Str::slug($page->title);
    //         }
    //     });

    //     static::updating(function ($page) {
    //         if (empty($page->slug)) {
    //             $page->slug = Str::slug($page->title);
    //         }
    //     });
    // }
}
