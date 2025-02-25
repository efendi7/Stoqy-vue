<?php

namespace App\Http\Controllers;

use App\Services\UserService; // Import UserService
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;
    protected $userService;

    public function __construct(ProductService $productService, UserService $userService)
    {
        $this->productService = $productService;
        $this->userService = $userService;
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        return view('products.index', compact('products', 'userRole'));
    }

    public function create()
    {
        $categories = $this->productService->getCategories();
        $suppliers = $this->productService->getSuppliers();
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        return view('products.create', compact('categories', 'suppliers', 'userRole'));
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
        ]);

        if ($userRole !== 'Admin') {
            return redirect()->route('products.index')->with('error', 'You do not have permission to add products.');
        }

        $this->productService->createProduct($request->all());

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = $this->productService->getCategories();
        $suppliers = $this->productService->getSuppliers();
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        return view('products.edit', compact('product', 'categories', 'suppliers', 'userRole'));
    }

    public function update(Request $request, Product $product)
    {
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
        ]);

        if ($userRole !== 'Admin') {
            return redirect()->route('products.index')->with('error', 'You do not have permission to update products.');
        }

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
