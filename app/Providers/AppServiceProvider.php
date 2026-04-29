<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CmsPage;
use App\Models\User;
use App\Models\ExpertSos;

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
        View::composer('frontend.layouts.partials.footer', function ($view) {
            $pages = CmsPage::where('status', 1)
                ->where('type', 'user')
                ->orderBy('sort_order')
                ->get();
            $view->with('cms_pages', $pages);
        });
        View::composer('*', function ($view) {

            $pendingExpertsCount = User::whereHas('expertDetail', function ($q) {
                $q->where('approval_status', 'pending');
            })->count();

            $view->with('pendingExpertsCount', $pendingExpertsCount);
        });
        View::composer('*', function ($view) {

            // 🚨 SOS Count (pending + in_progress)
            $pendingSosCount = ExpertSos::whereIn('status', ['pending', 'in_progress'])->count();

            $view->with('pendingSosCount', $pendingSosCount);
        });
    }
}
