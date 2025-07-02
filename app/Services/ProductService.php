<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(): LengthAwarePaginator
    {
        return $this->productRepository->getAllProducts();
    }

    public function getProductById($id): ?Product
    {
        return $this->productRepository->getProductById($id);
    }

    public function createProduct(array $data): Product
    {
        try {
            Log::info('Creating product with data', $data);

            // Handle image upload
            $imagePath = null;
            if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
                Log::info('Processing image upload');
                
                // Pastikan direktori ada
                if (!Storage::disk('public')->exists('product_images')) {
                    Storage::disk('public')->makeDirectory('product_images');
                }
                
                $imagePath = $data['image']->store('product_images', 'public');
                Log::info('Image stored at: ' . $imagePath);
            }
            
            $data['image'] = $imagePath;

            // Pastikan semua field required ada
            $requiredFields = ['name', 'sku', 'category_id', 'supplier_id', 'stock', 'initial_stock', 'purchase_price', 'sale_price'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field])) {
                    throw new \Exception("Field {$field} is required but missing");
                }
            }

            // Set default values
            $data['minimum_stock'] = $data['minimum_stock'] ?? 0;
            
            Log::info('Final data before repository create', $data);

            $product = $this->productRepository->createProduct($data);
            
            if ($product) {
                $this->logActivity("Menambahkan produk: {$product->name}", $product);
                Log::info('Product created successfully', ['product_id' => $product->id]);
            }

            return $product;
            
        } catch (\Exception $e) {
            Log::error('Error in createProduct service', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function updateProduct($id, array $data): bool
    {
        $existingProduct = $this->productRepository->getProductById($id);
        if (!$existingProduct) {
            Log::error("ProductService: Produk dengan ID $id tidak ditemukan.");
            return false;
        }

        // Handle image update
        if (isset($data['image'])) {
            if ($data['image'] instanceof \Illuminate\Http\UploadedFile) {
                // Delete old image if exists
                if ($existingProduct->image) {
                    Storage::disk('public')->delete($existingProduct->image);
                }
                $data['image'] = $data['image']->store('product_images', 'public');
            } elseif (is_null($data['image'])) {
                // Remove image
                if ($existingProduct->image) {
                    Storage::disk('public')->delete($existingProduct->image);
                }
                $data['image'] = null;
            }
        }

        $oldData = $existingProduct->toArray();
        $updated = $this->productRepository->updateProduct($id, $data);
        
        if ($updated) {
            $this->logActivity("Memperbarui produk: {$existingProduct->name}", $existingProduct, $oldData);
        }

        return $updated;
    }

    public function deleteProduct($id): bool
    {
        $product = $this->productRepository->getProductById($id);
        if (!$product) {
            Log::error("ProductService: Produk dengan ID $id tidak ditemukan.");
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

    public function searchProducts($search, $status): LengthAwarePaginator
    {
        return $this->productRepository->searchProducts($search, $status);
    }

    public function logActivity($action, $product, $oldData = null)
    {
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role ?? 'unknown',
                'action' => $action,
                'properties' => json_encode([
                    'product_id' => $product->id,
                    'before' => $oldData,
                    'after' => $product->toArray(),
                ]),
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging activity: ' . $e->getMessage());
        }
    }

    public function updateProductStock($productId, $newStock)
    {
        $product = $this->productRepository->getProductById($productId);

        if (!$product) {
            Log::error("ProductService: Produk dengan ID $productId tidak ditemukan.");
            return false;
        }

        $oldStock = $product->stock;
        $product->stock = $newStock;
        $product->save();

        $this->logActivity("Memperbarui stok produk: {$product->name}", $product, ['stock' => $oldStock]);
        return true;
    }

    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function importProducts($file)
    {
        Excel::import(new ProductsImport, $file);
    }

    public function getCategories()
    {
        return Category::select('id', 'name')->get();
    }

    public function getSuppliers()
    {
        return Supplier::select('id', 'name')->get();
    }

    public function validateProductData(array $data, $productId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku' . ($productId ? ",$productId" : ''),
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'stock' => 'required|integer|min:0',
            'initial_stock' => 'required|integer|min:0',
            'minimum_stock' => 'nullable|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'name.required' => 'Nama produk harus diisi',
            'sku.required' => 'SKU harus diisi',
            'sku.unique' => 'SKU sudah digunakan',
            'category_id.required' => 'Kategori harus dipilih',
            'category_id.exists' => 'Kategori tidak valid',
            'supplier_id.required' => 'Supplier harus dipilih',
            'supplier_id.exists' => 'Supplier tidak valid',
            'stock.required' => 'Stok harus diisi',
            'stock.min' => 'Stok tidak boleh kurang dari 0',
            'initial_stock.required' => 'Stok awal harus diisi',
            'initial_stock.min' => 'Stok awal tidak boleh kurang dari 0',
            'minimum_stock.min' => 'Stok minimum tidak boleh kurang dari 0',
            'purchase_price.required' => 'Harga beli harus diisi',
            'purchase_price.min' => 'Harga beli tidak boleh kurang dari 0',
            'sale_price.required' => 'Harga jual harus diisi',
            'sale_price.min' => 'Harga jual tidak boleh kurang dari 0',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
            'image.max' => 'Ukuran gambar maksimal 2MB',
        ];

        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            Log::warning('Validation failed', ['errors' => $validator->errors()]);
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}