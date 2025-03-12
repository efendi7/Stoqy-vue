<?php 
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;
use App\Interfaces\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Interfaces\StockTransactionRepositoryInterface;
use App\Repositories\StockTransactionRepository;
use App\Interfaces\StockOpnameRepositoryInterface;
use App\Repositories\StockOpnameRepository;

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
