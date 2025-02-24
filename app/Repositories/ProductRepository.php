<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts(): LengthAwarePaginator
    {
        return Product::with('category', 'supplier')->paginate(10);
    }

    public function getProductById($id): Product
    {
        return Product::with('category', 'supplier')->findOrFail($id);
    }

    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct($id, array $data): bool
    {
        $product = Product::findOrFail($id);
        return $product->update($data);
    }

    public function deleteProduct($id): bool
    {
        return Product::destroy($id);
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function getSuppliers()
    {
        return Supplier::all();
    }
}
