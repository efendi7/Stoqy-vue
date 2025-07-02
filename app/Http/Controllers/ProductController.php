<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\Category; // Add this line
use App\Models\Supplier; // Add this line

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
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('products.index', compact('products', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        try {
            // Log request data untuk debugging
            Log::info('Store request received', [
                'data' => $request->except(['image', '_token']),
                'has_file' => $request->hasFile('image'),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
            ]);

            // Validasi data menggunakan ProductService
            $validated = $this->productService->validateProductData($request->all());

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image');
                Log::info('Image file found', [
                    'original_name' => $request->file('image')->getClientOriginalName(),
                    'size' => $request->file('image')->getSize(),
                    'mime_type' => $request->file('image')->getMimeType(),
                ]);
            } else {
                $validated['image'] = null;
                Log::info('No image file uploaded');
            }

            // Pastikan minimum_stock memiliki default value
            $validated['minimum_stock'] = $validated['minimum_stock'] ?? 0;

            // Set initial_stock sama dengan stock jika tidak ada
            if (!isset($validated['initial_stock']) || $validated['initial_stock'] === null) {
                $validated['initial_stock'] = $validated['stock'];
            }

            Log::info('Validated data before create', $validated);

            // Create product
            $product = $this->productService->createProduct($validated);

            Log::info('Product created successfully', ['product_id' => $product->id]);

            // Return JSON response untuk AJAX
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan!',
                    'product' => $product->load(['category', 'supplier']), // Load relationships
                ],
                201,
            );
        } catch (ValidationException $e) {
            Log::warning('Validation failed', ['errors' => $e->errors()]);
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            Log::error('Error creating product', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan server saat menambahkan produk: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    // Method lainnya tetap sama...
    public function update(Request $request, $id)
    {
        try {
            Log::info('Update request received', [
                'id' => $id,
                'method' => $request->method(),
                'data' => $request->except(['image', '_token']),
            ]);

            $validated = $this->productService->validateProductData($request->all(), $id);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image');
            } else {
                $product = $this->productService->getProductById($id);
                if ($product) {
                    $validated['image'] = $product->image;
                }
            }

            $updated = $this->productService->updateProduct($id, $validated);

            if ($updated) {
                // Ambil data produk terbaru dengan relationships
                $product = $this->productService->getProductById($id);
                $product->load(['category', 'supplier']); // Load relationships

                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Produk berhasil diperbarui!',
                        'product' => $product, // Kirim data produk yang sudah diperbarui
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Produk tidak ditemukan atau gagal diperbarui.',
                    ],
                    404,
                );
            }
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'success' => false,
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            Log::error('Error updating product', [
                'id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan server saat memperbarui produk.',
                ],
                500,
            );
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->productService->deleteProduct($id);

            if ($deleted) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Produk berhasil dihapus!',
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Produk tidak ditemukan atau gagal dihapus.',
                    ],
                    404,
                );
            }
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan server saat menghapus produk.',
                ],
                500,
            );
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Produk tidak ditemukan.',
                    ],
                    404,
                );
            }

            // Load relationships
            $product->load(['category', 'supplier']);

            // Jika request AJAX, return JSON
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'product' => $product,
                ]);
            }

            // Jika bukan AJAX, return view (untuk backward compatibility)
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error fetching product', [
                'id' => $id,
                'message' => $e->getMessage(),
            ]);

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Terjadi kesalahan server.',
                    ],
                    500,
                );
            }

            return redirect()->route('products.index')->with('error', 'Produk tidak ditemukan.');
        }
    }

    public function edit($id)
    {
        $product = $this->productService->getProductById($id);
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $suppliers = $this->supplierService->getAllSuppliers();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function export()
    {
        return $this->productService->exportProducts();
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $this->productService->importProducts($request->file('file'));
            return redirect()->route('products.index')->with('success', 'Produk berhasil diimpor!');
        } catch (\Exception $e) {
            Log::error('Error importing products: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'Gagal mengimpor produk: ' . $e->getMessage());
        }
    }
    
    public function getCategories()
    {
        try {
            $categories = Category::select('id', 'name')
                ->orderBy('name')
                ->get();
            
            return response()->json($categories);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load categories'], 500);
        }
    }

    /**
     * Get all suppliers for AJAX requests
     */
    public function getSuppliers()
    {
        try {
            $suppliers = Supplier::select('id', 'name')
                ->orderBy('name')
                ->get();
            
            return response()->json($suppliers);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load suppliers'], 500);
        }
    }
}
