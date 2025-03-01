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
            'new_stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($request->product_id);
        $product->stock = $request->new_stock;
        $product->save();

        return redirect()->back()->with('success', 'Stok berhasil diperbarui.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'actual_stock' => 'required|integer|min:0',
        ]);
    
        $product = Product::findOrFail($id);
    
        // Update stok di tabel Product
        $product->stock = $request->actual_stock;
        $product->save(); // Simpan perubahan ke database
    
        // Cek apakah sudah ada data stock opname untuk produk ini
        $stockOpname = StockOpname::where('product_id', $product->id)->first();
    
        if ($stockOpname) {
            // Jika sudah ada, update datanya
            $stockOpname->update([
                'actual_stock' => $request->actual_stock,
            ]);
        } else {
            // Jika belum ada, buat data baru di tabel stock_opname
            StockOpname::create([
                'product_id' => $product->id,
                'actual_stock' => $request->actual_stock,
            ]);
        }
    
        return redirect()->back()->with('success', 'Stock opname berhasil diperbarui!');
    }
    

}
