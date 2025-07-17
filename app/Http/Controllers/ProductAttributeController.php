<?php
namespace App\Http\Controllers;

use App\Models\ProductAttribute;
use App\Services\ProductAttributeService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;

class ProductAttributeController extends Controller
{
    protected $productAttributeService;

    public function __construct(ProductAttributeService $productAttributeService)
    {
        $this->productAttributeService = $productAttributeService;
    }

    // Tampilkan halaman index Inertia
    public function index(Request $request)
    {
        // Ambil parameter search dari request
        $search = $request->get('search');
        
        // Pass search parameter ke service
        $attributes = $this->productAttributeService->getAllProductAttributes($search);

        return Inertia::render('ProductAttributes/Index', [
            'productAttributes' => $attributes,
            'products' => Product::select('id', 'name')->get(),
            'flash' => [
                'success' => session('success'),
            ],
            // Pass filters kembali ke frontend
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    // Store data atribut baru
    public function store(Request $request)
    {
        $validatedData = $this->productAttributeService->validateProductAttributeData($request->all());
        $this->productAttributeService->createProductAttribute($validatedData);

        return redirect()->back()->with('success', 'Atribut berhasil ditambahkan.');
    }

    // Update atribut
    public function update(Request $request, ProductAttribute $productAttribute)
    {
        $validatedData = $this->productAttributeService->validateProductAttributeData($request->all());
        $this->productAttributeService->updateProductAttribute($productAttribute->id, $validatedData);

        return redirect()->back()->with('success', 'Atribut berhasil diperbarui.');
    }

    // Hapus atribut
    public function destroy(ProductAttribute $productAttribute)
    {
        $this->productAttributeService->deleteProductAttribute($productAttribute->id);

        return redirect()->back()->with('success', 'Atribut berhasil dihapus.');
    }
}