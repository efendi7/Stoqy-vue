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

    // Menampilkan daftar atribut produk
    public function index()
    {
        $attributes = $this->productAttributeService->getAllProductAttributes();
        return view('product_attributes.index', ['productAttributes' => $attributes]);
    }

    // Menampilkan form untuk membuat atribut baru
    public function create()
    {
        $products = $this->productAttributeService->getAllProducts();
        return view('product_attributes.create', compact('products'));
    }

    // Menyimpan atribut baru
    public function store(Request $request)
    {
        $validatedData = $this->productAttributeService->validateProductAttributeData($request->all());

        $this->productAttributeService->createProductAttribute($validatedData);

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully added!');
    }

    // Menampilkan form edit atribut
    public function edit(ProductAttribute $productAttribute)
    {
        return view('product_attributes.edit', compact('productAttribute'));
    }

    // Menyimpan hasil edit atribut
    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $validatedData = $this->productAttributeService->validateProductAttributeData($request->all());

        $this->productAttributeService->updateProductAttribute($productAttribute->id, $validatedData);

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully updated!');
    }

    // Menghapus atribut produk
    public function destroy(ProductAttribute $productAttribute)
    {
        $this->productAttributeService->deleteProductAttribute($productAttribute->id);

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully deleted!');
    }
}
