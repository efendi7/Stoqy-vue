<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Services\ProductService; // Anda bisa tetap gunakan service ini
use Illuminate\Http\Request;
use Inertia\Inertia; // <-- Import Inertia
use Illuminate\Support\Facades\Redirect; // <-- Import Redirect

class ProductController extends Controller
{
    protected $productService;

    // Constructor bisa disederhanakan jika tidak butuh service lain di sini
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Menampilkan halaman daftar produk.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 5);
        // Ambil data produk dengan filter dan relasi
        $products = $this->productService->searchProducts($request->input('search'), $request->input('status'),$perPage);
        
        $categories = Category::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'filters' => $request->only(['search', 'status']), 
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(Request $request)
    {
        // Validasi bisa langsung di sini atau tetap di service
        $validated = $this->productService->validateProductData($request->all());
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        }

        $this->productService->createProduct($validated);

        // Redirect dengan flash message. Inertia akan menanganinya.
        return Redirect::route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Memperbarui produk.
     */
    public function update(Request $request, $id)
    {
        // Validasi untuk update
        $validated = $this->productService->validateProductData($request->all(), $id);

        // Penting: Inertia tidak mengirim file kosong, jadi kita perlu handle
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image');
        } else {
            // Jangan ubah gambar jika tidak ada file baru yang di-upload
            unset($validated['image']);
        }
        
        $this->productService->updateProduct($id, $validated);

        return Redirect::route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk.
     */
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return Redirect::route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
    
    // Method lain seperti create, edit, show, getCategories, getSuppliers
    // tidak lagi diperlukan di sini karena logikanya pindah ke komponen Vue.
}