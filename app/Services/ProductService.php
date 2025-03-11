<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;

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

        // Menangani upload gambar jika ada
        if (isset($data['image']) && is_object($data['image'])) {
            $imagePath = $data['image']->store('product_images', 'public');
            $data['image'] = $imagePath;
        }

        // Membuat produk melalui repository
        $product = $this->productRepository->createProduct($data);

        \Log::info('ProductService: Produk berhasil dibuat', ['product' => $product]);

        return $product;
    }
    public function updateProductStock($productId, $newStock)
{
    $product = $this->productRepository->getProductById($productId);

    if (!$product) {
        \Log::error("ProductService: Produk dengan ID $productId tidak ditemukan.");
        return false;
    }

    $updateData = ['stock' => $newStock];

    return $this->productRepository->updateProduct($productId, $updateData);
}


    public function updateProduct($id, array $data): bool
    {
        // Ambil data produk lama untuk referensi
        $existingProduct = $this->productRepository->getProductById($id);

        if (!$existingProduct) {
            \Log::error("ProductService: Produk dengan ID $id tidak ditemukan.");
            return false;
        }

        // Menangani update gambar jika ada gambar baru
        if (isset($data['image']) && is_object($data['image'])) {
            // Hapus gambar lama jika ada
            if ($existingProduct->image) {
                Storage::disk('public')->delete($existingProduct->image);
            }
            // Simpan gambar baru
            $data['image'] = $data['image']->store('product_images', 'public');
        } else {
            // Jika tidak ada gambar baru, gunakan gambar lama
            $data['image'] = $existingProduct->image;
        }

        // Update produk di repository
        return $this->productRepository->updateProduct($id, $data);
    }

    public function deleteProduct($id): bool
    {
        // Ambil data produk sebelum dihapus
        $product = $this->productRepository->getProductById($id);

        if (!$product) {
            \Log::error("ProductService: Produk dengan ID $id tidak ditemukan.");
            return false;
        }

        // Hapus gambar produk jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

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
