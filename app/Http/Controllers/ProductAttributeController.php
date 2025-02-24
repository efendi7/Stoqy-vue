<?php

namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use App\Services\ProductAttributeService;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    protected $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService)
    {
        $this->productAttributeService = $productAttributeService;
    }

    public function index()
    {
        $productAttributes = $this->productAttributeService->getAllProductAttributes();
        return view('product_attributes.index', compact('productAttributes'));
    }

    public function create()
    {
        $products = $this->productAttributeService->getAllProducts();
        return view('product_attributes.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute_name' => 'required|string|max:255',
            'attribute_value' => 'required|string|max:255',
        ]);

        $this->productAttributeService->createProductAttribute($request->all());

        return redirect()->route('product_attributes.index')->with('success', 'Atribut produk berhasil ditambahkan!');
    }

    public function edit(ProductAttribute $productAttribute)
    {
        return view('product_attributes.edit', compact('productAttribute'));
    }

    public function show(ProductAttribute $productAttribute)
    {
        return view('product_attributes.show', compact('productAttribute'));
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute_name' => 'required|string|max:255',
            'attribute_value' => 'required|string|max:255',
        ]);

        $this->productAttributeService->updateProductAttribute($productAttribute->id, $request->all());

        return redirect()->route('product_attributes.index')->with('success', 'Atribut produk berhasil diperbarui!');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $this->productAttributeService->deleteProductAttribute($productAttribute->id);
        return redirect()->route('product_attributes.index')->with('success', 'Atribut produk berhasil dihapus!');
    }
}
