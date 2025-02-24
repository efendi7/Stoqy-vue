<?php

namespace App\Providers;

use App\Interfaces\{
    ProductRepositoryInterface,
    CategoryRepositoryInterface,
    SupplierRepositoryInterface,
    ProductAttributeRepositoryInterface,
    StockTransactionRepositoryInterface,
    UserRepositoryInterface
};
use App\Repositories\{
    ProductRepository,
    CategoryRepository,
    SupplierRepository,
    ProductAttributeRepository,
    StockTransactionRepository,
    UserRepository
};
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        $this->app->bind(
            SupplierRepositoryInterface::class,
            SupplierRepository::class
        );

        $this->app->bind(
            ProductAttributeRepositoryInterface::class,
            ProductAttributeRepository::class
        );

        $this->app->bind(
            StockTransactionRepositoryInterface::class,
            StockTransactionRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
    }

    public function boot()
    {
        //
    }
}
