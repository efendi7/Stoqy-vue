<?php
namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;

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

    public function getProductById($id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct(array $data): Product
{
    \Log::info('ProductService: Meneruskan data ke repository', ['data' => $data]);

    $product = $this->productRepository->createProduct($data);

    \Log::info('ProductService: Produk berhasil dibuat', ['product' => $product]);

    return $product;
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

    public function exportProducts()
{
    return Excel::download(new ProductsExport, 'products.xlsx');
}

public function importProducts($file)
{
    Excel::import(new ProductsImport, $file);
}

}



