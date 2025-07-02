<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\RoleRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['partials.navbar', 'partials.sidebar'], function ($view) {
            $setting = Setting::first();
            $pendingRequests = RoleRequest::where('status', 'pending')->count();

            $view->with('setting', $setting)
                 ->with('pendingRequests', $pendingRequests);
        });
    }
}