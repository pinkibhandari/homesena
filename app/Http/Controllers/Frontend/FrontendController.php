<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;

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
        //  Slug ke basis par page fetch karo
        $page = CmsPage::where('slug', $slug)
                ->where('type', 'user')
                ->where('status', 1)
                ->firstOrFail();
        return view('frontend.pages.cms', compact('page'));
    }
}