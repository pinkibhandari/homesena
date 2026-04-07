<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FrontendPage;

class FrontendController extends Controller
{
    /**
     * Home Page
     */
    public function home()
    {
        return view('frontend.home');
    }

    /**
     * Dynamic CMS Page (Fully Dynamic)
     */
    public function page($slug)
    {
        // ✅ Slug ke basis par page fetch karo
        $page = FrontendPage::where('slug', $slug)
            ->where('status', 1)
            ->first();

        if (!$page) {
            abort(404, 'Page not found');
        }

        // ✅ Dynamic view load karo
        return view('frontend.pages.dynamic-page', compact('page'));
    }
}