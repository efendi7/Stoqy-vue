<?php

namespace App\Http\Controllers;

use App\Models\StockTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    public function index()
{
    $transactions = StockTransaction::with('product', 'user')->get()->map(function ($transaction) {
        $transaction->type = $transaction->type === 'Masuk' ? 'in' : 'out';
        return $transaction;
    });
        return view('stock_transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('stock_transactions.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        \Log::info('Data yang diterima di metode store: ', $request->all());

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
        ]);

        // Gunakan current date jika transaction_date tidak disertakan
        $transactionDate = $request->transaction_date ?? date('Y-m-d');

        // Log the transaction date
        \Log::info('Tanggal Transaksi: ', ['transaction_date' => $transactionDate]);

        // Buat transaksi baru dengan menyertakan transaction_date
        $transaction = StockTransaction::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'transaction_date' => $transactionDate
        ]);

        // Update stok produk
        $product = Product::find($request->product_id);
        if ($request->type == 'Masuk') {
            $product->stock += $request->quantity;
        } else {
            $product->stock -= $request->quantity;
        }
        $product->save();

        \Log::info('Transaksi stok berhasil dicatat: ', $transaction->toArray());

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dicatat!');
    }

    public function edit($id)
    {
        $transaction = StockTransaction::find($id);
        $products = Product::all();
        $users = User::all();
        return view('stock_transactions.edit', compact('transaction', 'products', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ]);

        $transaction = StockTransaction::find($id);
        $transaction->update($request->all());

        // Update stok produk
        $product = Product::find($request->product_id);
        if ($request->type == 'Masuk') {
            $product->stock += $request->quantity;
        } else {
            $product->stock -= $request->quantity;
        }
        $product->save();

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = StockTransaction::find($id);
        
        // Update stock before deleting transaction
        $product = Product::find($transaction->product_id);
        if ($transaction->type == 'Masuk') {
            $product->stock -= $transaction->quantity;
        } else {
            $product->stock += $transaction->quantity;
        }
        $product->save();

        $transaction->delete();

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus!');
    }
}
