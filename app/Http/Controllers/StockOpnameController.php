<?php 

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Services\StockOpnameService; 
use App\Models\Product;

class StockOpnameController extends Controller 
{     
    protected $stockOpnameService;

    public function __construct(StockOpnameService $stockOpnameService)
    {
        $this->stockOpnameService = $stockOpnameService;
    }
    public function index()
    {
    $products = Product::with('stockOpname')->paginate(10);
    return view('stock_opname.index', compact('products'));
    return view('stock_opname.index');
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'recorded_stock' => 'required|integer|min:0',
            'actual_stock' => 'required|integer|min:0',
            'difference' => 'required|integer'
        ]);

        $this->stockOpnameService->storeStockOpname($request->all());

        return redirect()->back()->with('success', 'Stock opname berhasil disimpan.');
    }
}
