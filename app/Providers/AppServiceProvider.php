<?php 
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

// Import semua interface dan repository yang digunakan
use App\Interfaces\DashboardRepositoryInterface;
use App\Repositories\DashboardRepository;

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

use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

use App\Interfaces\ProductAttributeRepositoryInterface;
use App\Repositories\ProductAttributeRepository;

use App\Interfaces\SupplierRepositoryInterface;
use App\Repositories\SupplierRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind semua repository
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(StockTransactionRepository::class, StockTransactionRepository::class);
        $this->app->bind(StockOpnameRepositoryInterface::class, StockOpnameRepository::class);
        $this->app->bind(StockReportRepositoryInterface::class, StockReportRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class, SupplierRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);

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
