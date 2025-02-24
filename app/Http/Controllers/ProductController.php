<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index()
    {
        $products = $this->productService->getAllProducts();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->productService->getCategories();
        $suppliers = $this->productService->getSuppliers();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
        ]);

        $this->productService->createProduct($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = $this->productService->getCategories();
        $suppliers = $this->productService->getSuppliers();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
        ]);

        $this->productService->updateProduct($product->id, $request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }
    
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product->id);
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    public function show($id)
    {
        $product = $this->productService->getProductById($id);
        return view('products.show', compact('product'));
    }
    

}
