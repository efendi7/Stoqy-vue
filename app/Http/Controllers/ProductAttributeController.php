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
        $attributes = $this->productAttributeService->getAllAttributes();
        return view('product_attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('product_attributes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $this->productAttributeService->createAttribute($request->all());

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully added!');
    }

    public function edit(ProductAttribute $productAttribute)
    {
        return view('product_attributes.edit', compact('productAttribute'));
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255',
        ]);

        $this->productAttributeService->updateAttribute($productAttribute->id, $request->all());

        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully updated!');
    }

    public function destroy(ProductAttribute $productAttribute)
    {
        $this->productAttributeService->deleteAttribute($productAttribute->id);
        return redirect()->route('product_attributes.index')->with('success', 'Product attribute successfully deleted!');
    }
}
