<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockOpname;


class StockOpnameController extends Controller
{
    public function index()
    {
        // Ambil data produk untuk ditampilkan di halaman stock opname
        $products = Product::all();
        return view('stock_opname.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'actual_stock' => 'required|integer|min:0',
        ]);
    
        $product = Product::findOrFail($request->product_id);
        $difference = $request->actual_stock - $product->stock;
    
        // Simpan hasil stock opname ke database
        $stockOpname = StockOpname::create([
            'product_id' => $product->id,
            'actual_stock' => $request->actual_stock,
            'difference' => $difference,
        ]);
    
        if ($stockOpname) {
            return redirect()->back()->with('success', 'Stock audit berhasil disimpan!');
        } else {
            return redirect()->back()->with('error', 'Gagal menyimpan stock audit.');
        }
    }
    
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'actual_stock' => 'required|integer|min:0',
        ]);
    
        $product = Product::findOrFail($id);
        
        // Update stok produk agar sesuai dengan stok fisik
        $product->stock = $request->actual_stock;
        $product->save();
    
        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    }
    

}
