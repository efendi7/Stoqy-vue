<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
// use App\Models\Category; // Tidak diperlukan di sini jika hanya digunakan di service atau controller
// use App\Models\Supplier; // Tidak diperlukan di sini jika hanya digunakan di service atau controller
use App\Models\StockTransaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Tambahkan ini

class ProductRepository implements ProductRepositoryInterface
{
    // Ambil semua produk dengan pagination
    public function getAllProducts(): LengthAwarePaginator
    {
        return Product::with(['category', 'supplier'])->paginate(10);
    }

    // Cari produk berdasarkan ID
    public function getProductById($id): ?Product
    {
        return Product::with(['category', 'supplier'])->find($id);
    }

    // Tambahkan metode ini untuk memenuhi kontrak interface (jika findOrFail diperlukan)
    // Sebaiknya service yang menangani ProductNotFound, repository hanya find()
    public function findById($id): Product
    {
        return Product::findOrFail($id);
    }

    // Buat produk baru
    public function createProduct(array $data): Product
    {
        // Penentuan default untuk purchase_price, initial_stock, minimum_stock
        // sebaiknya sudah ditangani di ProductService atau validasi.
        // Repository seharusnya hanya fokus pada penyimpanan data yang sudah bersih.
        // Namun, jika Anda ingin ada fallback di sini:
        $data['purchase_price'] = $data['purchase_price'] ?? 0;
        $data['initial_stock'] = $data['initial_stock'] ?? $data['stock'] ?? 0; // Fallback ganda
        $data['minimum_stock'] = $data['minimum_stock'] ?? 0;

        return Product::create($data);
    }

    /**
     * Update produk.
     *
     * @param int $id ID produk yang akan diperbarui.
     * @param array $data Data untuk update.
     * @return bool True jika berhasil diupdate, false jika produk tidak ditemukan.
     */
    public function updateProduct($id, array $data): bool
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        return $product->update($data);
    }

    // Hapus produk dan semua transaksi terkait
    public function deleteProduct($id): bool
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        // Hapus transaksi stok terkait
        StockTransaction::where('product_id', $id)->delete();

        return $product->delete();
    }

    // Cari produk dengan filter pencarian dan status
    public function searchProducts($search, $status): LengthAwarePaginator
    {
        $query = Product::query()->with(['category', 'supplier']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($cat) use ($search) {
                        $cat->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status) {
            $query->where(function ($q) use ($status) {
                if ($status === 'Habis') {
                    $q->where('stock', 0);
                } elseif ($status === 'Warning') {
                    // Pastikan minimum_stock tidak null untuk perbandingan
                    $q->whereColumn('stock', '<', 'minimum_stock')->where('stock', '>', 0);
                } elseif ($status === 'Tersedia') {
                    // Pastikan minimum_stock tidak null untuk perbandingan
                    $q->whereColumn('stock', '>=', 'minimum_stock');
                }
            });
        }

        return $query->paginate(10)->appends(request()->query());
    }

    // Pindahkan getCategories dan getSuppliers ke repository mereka masing-masing
    // public function getCategories()
    // {
    //     return Category::select('id', 'name')->get();
    // }

    // public function getSuppliers()
    // {
    //     return Supplier::select('id', 'name')->get();
    // }
}