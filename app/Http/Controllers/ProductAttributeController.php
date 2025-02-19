<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $productAttributes = ProductAttribute::all();
        return view('product_attributes.index', compact('productAttributes'));
    }

    public function create()
    {
        $products = Product::all(); // Ambil semua produk
        return view('product_attributes.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'attribute_name' => 'required|string|max:255',
            'attribute_value' => 'required|string|max:255',
        ]);

        ProductAttribute::create($request->all());

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

        $productAttribute->update($request->all());

        return redirect()->route('product_attributes.index')->with('success', 'Atribut produk berhasil diperbarui!');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();
        return redirect()->route('product_attributes.index')->with('success', 'Atribut produk berhasil dihapus!');
    }
}
