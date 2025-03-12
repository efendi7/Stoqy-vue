<?php 
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

// Import semua interface dan repository yang digunakan
use App\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;

use App\Interfaces\StockTransactionRepositoryInterface;
use App\Repositories\StockTransactionRepository;

use App\Interfaces\StockOpnameRepositoryInterface;
use App\Repositories\StockOpnameRepository;

use App\Interfaces\StockReportRepositoryInterface;
use App\Repositories\StockReportRepository;

use App\Interfaces\ActivityLogRepositoryInterface;
use App\Repositories\ActivityLogRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(StockTransactionRepositoryInterface::class, StockTransactionRepository::class);
        $this->app->bind(StockOpnameRepositoryInterface::class, StockOpnameRepository::class);
        $this->app->bind(StockReportRepositoryInterface::class, StockReportRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tambahkan View Composer agar setting tersedia di semua view
        View::composer('*', function ($view) {
            $view->with('setting', Setting::first());
        });
    }
}
