<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockTransaction;
use Illuminate\Pagination\LengthAwarePaginator;

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

    // Tambahkan metode ini untuk memenuhi kontrak interface
    public function findById($id)
    {
        return Product::findOrFail($id);
    }

    // Buat produk baru
    public function createProduct(array $data): Product
    {
        if (!isset($data['purchase_price'])) {
            $data['purchase_price'] = 0;  // Berikan nilai default jika tidak diisi
        }
    
        return Product::create($data);
    }
    

    // Update produk
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
                    $q->whereColumn('stock', '<', 'minimum_stock')->where('stock', '>', 0);
                } elseif ($status === 'Tersedia') {
                    $q->whereColumn('stock', '>=', 'minimum_stock');
                }
            });
        }

        return $query->paginate(10)->appends(request()->query());
    }

    // Ambil semua kategori
    public function getCategories()
    {
        return Category::select('id', 'name')->get();
    }

    // Ambil semua supplier
    public function getSuppliers()
    {
        return Supplier::select('id', 'name')->get();
    }
}
