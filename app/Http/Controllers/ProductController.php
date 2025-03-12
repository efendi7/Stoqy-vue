<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\SupplierService;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $supplierService;

    public function __construct(ProductService $productService, CategoryService $categoryService, SupplierService $supplierService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
        $this->supplierService = $supplierService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->searchProducts($request->input('search'), $request->input('status'));
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'sku' => 'required|string|unique:products,sku',
        'category_id' => 'required|exists:categories,id',
        'supplier_id' => 'required|exists:suppliers,id',
        'stock' => 'required|integer|min:0',
        'minimum_stock' => 'nullable|integer|min:0',
        'purchase_price' => 'required|numeric|min:0', // Tambahkan validasi purchase_price
        'sale_price' => 'required|numeric|min:0', // Tambahkan validasi sale_price
        'image' => 'nullable|image|max:2048',
    ]);

    $validated['minimum_stock'] = $validated['minimum_stock'] ?? 0;

    $product = $this->productService->createProduct($validated);

    return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
}



    public function create()
{
    $categories = $this->categoryService->getAllCategories();
    $suppliers = $this->supplierService->getAllSuppliers();

    return view('products.create', compact('categories', 'suppliers'));
}


public function update(Request $request, $id)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'stock' => 'required|integer|min:0',
        'minimum_stock' => 'required|integer|min:0',
        'purchase_price' => 'required|numeric|min:0', // Tambahkan validasi harga beli
        'sale_price' => 'required|numeric|min:0', // Tambahkan validasi harga jual
        'image' => 'nullable|image|max:2048',
    ]);

    $this->productService->updateProduct($id, $validated);
    return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
}

    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function export()
    {
        return $this->productService->exportProducts();
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx']);
        $this->productService->importProducts($request->file('file'));
        return redirect()->route('products.index')->with('success', 'Produk berhasil diimpor!');
    }
    public function show($id)
    {
    $product = $this->productService->getProductById($id);

    if (!$product) {
        return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
    }

    return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();
        
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

}
