<?php
namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts();
    }

    public function getProductById($id): Product
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct(array $data): Product
    {
        return $this->productRepository->createProduct($data);
    }

    public function updateProduct($id, array $data): bool
    {
        return $this->productRepository->updateProduct($id, $data);
    }

    public function deleteProduct($id): bool
    {
        return $this->productRepository->deleteProduct($id);
    }

    public function getCategories()
    {
        return $this->productRepository->getCategories();
    }

    public function getSuppliers()
    {
        return $this->productRepository->getSuppliers();
    }
}
