<?php
namespace App\Http\Controllers;

use App\Services\UserService;
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
        $userRole = $this->userService->getUserRole(auth()->id());
        return view('products.index', compact('products', 'userRole'));
    }

    public function create()
    {
        $categories = $this->productService->getCategories();
        $suppliers = $this->productService->getSuppliers();
        $userRole = $this->userService->getUserRole(auth()->id());
        return view('products.create', compact('categories', 'suppliers', 'userRole'));
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'minimum_stock' => 'required|integer|min:0',
        ]);

        if ($userRole !== 'admin') {
            return redirect()->route('products.index')->with('error', 'You do not have permission to add products.');
        }

        try {
            $this->productService->createProduct($request->all());
            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            \Log::error('Error creating product: ' . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->route('products.index')->with('error', 'Gagal menambahkan produk.');
        }
    }

    public function edit(Product $product)
    {
        $categories = $this->productService->getCategories();
        $suppliers = $this->productService->getSuppliers();
        $userRole = $this->userService->getUserRole(auth()->id());
        return view('products.edit', compact('product', 'categories', 'suppliers', 'userRole'));
    }

    public function update(Request $request, Product $product)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'purchase_price' => 'nullable|numeric',
            'sale_price' => 'nullable|numeric',
            'stock' => 'required|integer',
        ]);

        if ($userRole !== 'admin') {
            return redirect()->route('products.index')->with('error', 'You do not have permission to update products.');
        }

        try {
            $this->productService->updateProduct($product->id, $request->all());
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating product: ' . $e->getMessage(), ['request' => $request->all(), 'product_id' => $product->id]);
            return redirect()->route('products.index')->with('error', 'Gagal memperbarui produk.');
        }
    }

    public function destroy(Product $product)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'admin') {
            return redirect()->route('products.index')->with('error', 'You do not have permission to delete products.');
        }

        try {
            $this->productService->deleteProduct($product->id);
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            \Log::error('Error deleting product: ' . $e->getMessage(), ['product_id' => $product->id]);
            return redirect()->route('products.index')->with('error', 'Gagal menghapus produk.');
        }
    }

    public function show($id)
    {
        $product = $this->productService->getProductById($id);
        return view('products.show', compact('product'));
    }

    public function export()
    {
        return $this->productService->exportProducts();
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        $this->productService->importProducts($request->file('file'));
        return back()->with('success', 'Produk berhasil diimpor!');
    }
}
