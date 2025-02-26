<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
}
