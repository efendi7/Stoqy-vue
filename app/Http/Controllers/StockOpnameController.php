<?php 

namespace App\Http\Controllers;  

use Illuminate\Http\Request; 
use App\Models\Product; 
use App\Models\StockOpname;   
use App\Models\ActivityLog;

class StockOpnameController extends Controller 
{     
    public function index()     
    {         
        $products = Product::with('stockOpname')->get();
        return view('stock_opname.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'recorded_stock' => 'required|integer|min:0',
            'actual_stock' => 'required|integer|min:0',
            'difference' => 'required|integer'
        ]);

        $product = Product::findOrFail($request->product_id);
    
        $stockOpname = StockOpname::updateOrCreate(
            ['product_id' => $product->id],
            [
                'recorded_stock' => $request->recorded_stock,
                'actual_stock' => $request->actual_stock,
                'difference' => $request->difference,
                'updated_at' => now(),
            ]
        );

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Melakukan stock opname untuk produk: {$product->name}",
            'properties' => json_encode([
                'product_name' => $product->name,
                'recorded_stock' => $request->recorded_stock,
                'actual_stock' => $request->actual_stock,
                'difference' => $request->difference,
            ]),
        ]);
    
        return redirect()->back()->with('success', 'Stock opname berhasil disimpan.');
    }

    public function updateStockToActual(Request $request, $id)     
    {         
        $request->validate([             
            'actual_stock' => 'required|integer|min:0',         
        ]);              

        $product = Product::findOrFail($id);
        
        // Simpan stok sebelum perubahan
        $oldStock = $product->stock;
        $difference = $request->actual_stock - $oldStock;

        StockOpname::create([             
            'product_id' => $product->id,             
            'recorded_stock' => $oldStock, 
            'actual_stock' => $request->actual_stock,             
            'difference' => $difference,         
        ]);

        // Update stok produk agar sesuai dengan stok fisik
        $product->stock = $request->actual_stock;
        $product->save();

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Memperbarui stok produk: {$product->name}",
            'properties' => json_encode([
                'product_name' => $product->name,
                'old_stock' => $oldStock,
                'new_stock' => $request->actual_stock,
                'difference' => $difference,
            ]),
        ]);
              
        return redirect()->back()->with('success', 'Stok berhasil diperbarui ke stok fisik!');     
    }       
}
