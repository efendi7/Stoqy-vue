<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

// Existing bindings
use App\Interfaces\DashboardRepositoryInterface;
use App\Repositories\DashboardRepository;
use App\Interfaces\ActivityLogRepositoryInterface;
use App\Repositories\ActivityLogRepository;
use App\Interfaces\ProductAttributeRepositoryInterface;
use App\Repositories\ProductAttributeRepository;
use App\Interfaces\StockOpnameRepositoryInterface;
use App\Repositories\StockOpnameRepository;
use App\Interfaces\ProductImportExportRepositoryInterface;
use App\Repositories\ProductImportExportRepository;
// ==[ ADDED FOR STOCK REPORT ]==
use App\Interfaces\StockReportRepositoryInterface;
use App\Repositories\StockReportRepository;
use App\Interfaces\SettingRepositoryInterface;
use App\Repositories\SettingRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Dashboard
        $this->app->bind(
            DashboardRepositoryInterface::class,
            DashboardRepository::class
        );

        // Activity Log
        $this->app->bind(
            ActivityLogRepositoryInterface::class,
            ActivityLogRepository::class 
        );

        // Product Attribute
        $this->app->bind(
            ProductAttributeRepositoryInterface::class,
            ProductAttributeRepository::class
        );

        // Stock Opname
        $this->app->bind(
            StockOpnameRepositoryInterface::class,
            StockOpnameRepository::class
        );

        // ==[ ADDED FOR STOCK REPORT ]==
        $this->app->bind(
            StockReportRepositoryInterface::class,
            StockReportRepository::class
        );
         // 2. TAMBAHKAN BLOK INI UNTUK MEMPERBAIKI ERROR
        $this->app->bind(
            ProductImportExportRepositoryInterface::class,
            ProductImportExportRepository::class
        );
        $this->app->bind(
            SettingRepositoryInterface::class,
            SettingRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Inertia::share([
            'flash' => function () {
                return [
                    'success' => session('success'),
                    'error' => session('error'),
                ];
            },
        ]);
    }
}
