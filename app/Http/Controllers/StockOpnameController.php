<?php 

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Services\StockOpnameService; 
use App\Models\Product;
use Inertia\Inertia;

class StockOpnameController extends Controller 
{     
    protected $stockOpnameService;

    public function __construct(StockOpnameService $stockOpnameService)
    {
        $this->stockOpnameService = $stockOpnameService;
    }

public function index(Request $request)
{
    $filters = $request->only('search');

    $products = Product::with('stockOpname')
        ->when($request->input('search'), function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('StockOpname/Index', [
        'products' => $products,
        'filters' => $filters,
        'flash' => [
            'success' => session('success'),
        ],
    ]);
}
    public function store(Request $request)
    {
        $this->stockOpnameService->storeStockOpname($request->all());

        return redirect()->back()->with('success', 'Stock opname berhasil disimpan.');
    }

    public function updateStock(Request $request, $productId)
    {
        $success = $this->stockOpnameService->updateStockToActual($productId);

        if ($success) {
            return redirect()->back()->with('success', 'Stok berhasil diperbarui ke actual stock.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui stok.');
    }
}
