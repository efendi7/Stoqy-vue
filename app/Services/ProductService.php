<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ActivityLog;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    // Ambil semua produk dengan pagination
    public function getAllProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts();
    }

    // Cari produk berdasarkan ID
    public function getProductById($id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }

    // Buat produk baru
    public function createProduct(array $data): Product
    {
        if (isset($data['image']) && is_object($data['image'])) {
            $data['image'] = $data['image']->store('product_images', 'public');
        }

        $product = $this->productRepository->createProduct($data);
        $this->logActivity("Menambahkan produk: {$product->name}", $product);

        return $product;
    }

    // Update produk
    public function updateProduct($id, array $data): bool
    {
        $existingProduct = $this->productRepository->getProductById($id);
        if (!$existingProduct) {
            \Log::error("ProductService: Produk dengan ID $id tidak ditemukan.");
            return false;
        }
    
        // Handle update gambar jika ada
        if (isset($data['image']) && is_object($data['image'])) {
            if ($existingProduct->image) {
                Storage::disk('public')->delete($existingProduct->image);
            }
            $data['image'] = $data['image']->store('product_images', 'public');
        } else {
            $data['image'] = $existingProduct->image;
        }
    
        // ✨ Tambahkan ini untuk memastikan minimum_stock terupdate ✨
        if (isset($data['minimum_stock'])) {
            $existingProduct->minimum_stock = $data['minimum_stock'];
        }
    
        $updated = $this->productRepository->updateProduct($id, $data);
        if ($updated) {
            $this->logActivity("Memperbarui produk: {$existingProduct->name}", $existingProduct, $data);
        }
    
        return $updated;
    }
    
    // Hapus produk
    public function deleteProduct($id): bool
    {
        $product = $this->productRepository->getProductById($id);
        if (!$product) {
            \Log::error("ProductService: Produk dengan ID $id tidak ditemukan.");
            return false;
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $deleted = $this->productRepository->deleteProduct($id);
        if ($deleted) {
            $this->logActivity("Menghapus produk: {$product->name}", $product);
        }

        return $deleted;
    }

    // Cari produk dengan filter
    public function searchProducts($search, $status): LengthAwarePaginator
    {
        return $this->productRepository->searchProducts($search, $status);
    }

    // Log aktivitas
    public function logActivity($action, $product, $oldData = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => $action,
            'properties' => json_encode([
                'product_id' => $product->id,
                'before' => $oldData,
                'after' => $product->toArray(),
            ]),
        ]);
    }

    public function updateProductStock($productId, $newStock)
{
    $product = $this->productRepository->getProductById($productId);

    if (!$product) {
        \Log::error("ProductService: Produk dengan ID $productId tidak ditemukan.");
        return false;
    }

    $product->stock = $newStock;
    $product->save();

    $this->logActivity("Memperbarui stok produk: {$product->name}", $product, ['stock' => $newStock]);
    return true;
}


    // Export produk
    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    // Import produk
    public function importProducts($file)
    {
        Excel::import(new ProductsImport, $file);
    }

    // Ambil kategori dan supplier (bisa dimasukkan ke repository jika mau)
    public function getCategories()
    {
        return $this->productRepository->getCategories();
    }

    public function getSuppliers()
    {
        return $this->productRepository->getSuppliers();
    }
}
