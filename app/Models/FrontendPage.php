<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FrontendPage extends Model
{
    // ✅ Correct table name
    protected $table = 'cms_pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'menu_type', // footer/navbar ke liye
        'status'
    ];

    /**
     * Auto generate slug before saving
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    /**
     * Scope: Only Active Pages
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope: Footer Left
     */
    public function scopeFooterLeft($query)
    {
        return $query->where('menu_type', 'footer_left');
    }

    /**
     * Scope: Footer Right
     */
    public function scopeFooterRight($query)
    {
        return $query->where('menu_type', 'footer_right');
    }
}