<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CmsPage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View::composer('*', function ($view) {
        //     $view->with('frontend_pages', FrontendPage::where('status', 1)->get());
        // });

        View::composer('frontend.layouts.partials.footer', function ($view) {
            $pages = CmsPage::where('status', 1)
                ->where('type', 'user')
                ->orderBy('sort_order')
                ->get();
            $view->with('cms_pages', $pages);
        });
    }
}