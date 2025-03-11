<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use App\Models\Product;
use App\Services\ProductAttributeService;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ProductAttributeController extends Controller
{
    protected $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService)
    {
        $this->productAttributeService = $productAttributeService;
    }

    public function index()
    {
        $attributes = $this->productAttributeService->getAllProductAttributes();
        return view('product_attributes.index', ['productAttributes' => $attributes]);
    }

    public function create()
    {
        $products = $this->productAttributeService->getAllProducts();
        return view('product_attributes.create', compact('products'));
    }

    public function store(Request $request)
    {
        \Log::info('Store method hit', ['request' => $request->all()]);

        $validatedData = $request->validate([
            'attribute_name' => 'required|string|max:255',
            'attribute_value' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $attribute = $this->productAttributeService->createProductAttribute($validatedData);

        // Ambil nama produk berdasarkan ID
        $product = Product::find($validatedData['product_id']);
        $productName = $product ? $product->name : 'Unknown';

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menambahkan atribut produk '{$attribute->attribute_name}' untuk produk '{$productName}'",
            'properties' => json_encode([
                'attribute_id' => $attribute->id,
                'product_name' => $productName,
                'data' => $validatedData,
            ]),
        ]);

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully added!');
    }

    public function edit(ProductAttribute $productAttribute)
    {
        return view('product_attributes.edit', compact('productAttribute'));
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $request->validate([
            'attribute_name' => 'required|string|max:255',
            'attribute_value' => 'required|string|max:255',
        ]);

        // Simpan data lama sebelum update
        $oldData = $productAttribute->toArray();
        $data = $request->only(['attribute_name', 'attribute_value']);

        $this->productAttributeService->updateProductAttribute($productAttribute->id, $data);

        // Ambil nama produk berdasarkan ID
        $product = Product::find($productAttribute->product_id);
        $productName = $product ? $product->name : 'Unknown';

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Mengedit atribut produk '{$productAttribute->attribute_name}' untuk produk '{$productName}'",
            'properties' => json_encode([
                'product_name' => $productName,
                'before' => $oldData,
                'after' => $data,
            ]),
        ]);

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully updated!');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        // Simpan data sebelum dihapus
        $attributeData = $productAttribute->toArray();

        // Ambil nama produk berdasarkan ID
        $product = Product::find($productAttribute->product_id);
        $productName = $product ? $product->name : 'Unknown';

        $this->productAttributeService->deleteProductAttribute($productAttribute->id);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menghapus atribut produk '{$productAttribute->attribute_name}' dari produk '{$productName}'",
            'properties' => json_encode([
                'product_name' => $productName,
                'deleted_data' => $attributeData,
            ]),
        ]);

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully deleted!');
    }
}
