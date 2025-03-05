<?php 

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Models\Product; 
use App\Models\StockOpname;   

class StockOpnameController extends Controller 
{     
    public function index()     
    {         
        $products = Product::with('stockOpname')->get();
        return view('stock_opname.index', compact('products'));
    }
    
       

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'recorded_stock' => 'required|integer|min:0',
            'actual_stock' => 'required|integer|min:0',
            'difference' => 'required|integer'
        ]);
    
        // Simpan audit stok
        StockOpname::updateOrCreate(
            ['product_id' => $request->product_id],
            [
                'recorded_stock' => $request->recorded_stock,
                'actual_stock' => $request->actual_stock,
                'difference' => $request->difference,
                'updated_at' => now(),
            ]
        );
    
        return redirect()->back()->with('success', 'Stock opname berhasil disimpan.');
    }
    
    // Metode untuk update stok tercatat sesuai dengan stok fisik
    public function updateStockToActual(Request $request, $id)     
    {         
        $request->validate([             
            'actual_stock' => 'required|integer|min:0',         
        ]);              

        $product = Product::findOrFail($id);
        
        // Simpan stock opname untuk audit
        $difference = $request->actual_stock - $product->stock;
        StockOpname::create([             
            'product_id' => $product->id,             
            'recorded_stock' => $product->stock, 
            'actual_stock' => $request->actual_stock,             
            'difference' => $difference,         
        ]);

        // Update stok produk agar sesuai dengan stok fisik
        $product->stock = $request->actual_stock;
        $product->save();
              
        return redirect()->back()->with('success', 'Stok berhasil diperbarui ke stok fisik!');     
    }       
}