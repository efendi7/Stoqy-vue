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

   
    public function searchProducts($search, $status, $perPage): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category', 'supplier']) // Eager load relasi untuk performa
            
            // Terapkan filter pencarian hanya jika $search tidak kosong
            ->when($search, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                      ->orWhere('sku', 'like', "%{$term}%")
                      ->orWhereHas('category', function ($catQuery) use ($term) {
                          $catQuery->where('name', 'like', "%{$term}%");
                      });
                });
            })

            // Terapkan filter status hanya jika $status tidak kosong
            ->when($status, function ($query, $statusValue) {
                // Menggunakan 'match' (PHP 8.0+) untuk kode yang lebih bersih
                $query->where(function ($q) use ($statusValue) {
                    match ($statusValue) {
                        'Habis' => $q->where('stock', '=', 0),
                        'Warning' => $q->whereColumn('stock', '<', 'minimum_stock')->where('stock', '>', 0),
                        'Tersedia' => $q->whereColumn('stock', '>=', 'minimum_stock'),
                        default => null, // Abaikan jika status tidak dikenali
                    };
                });
            })

            ->latest() // Urutkan dari yang terbaru
            ->paginate($perPage) // <-- BUG DIPERBAIKI: Gunakan variabel $perPage
            ->withQueryString(); // <-- Cara modern untuk mempertahankan query string
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