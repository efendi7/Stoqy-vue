<?php

namespace App\Http\Controllers;

use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    protected $stockTransactionService;

    public function __construct(StockTransactionService $stockTransactionService)
    {
        $this->stockTransactionService = $stockTransactionService;
    }

    public function index()
    {
        $transactions = $this->stockTransactionService->getAllTransactions();
        return view('stock_transactions.index', compact('transactions'));
    }

    public function stockOpname()
    {
        $products = Product::all();
        return view('stock_transactions.opname', compact('products'));
    }

    public function setMinimumStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'minimum_stock' => 'required|integer|min:0'
        ]);

        $product = Product::find($request->product_id);
        $product->minimum_stock = $request->minimum_stock;
        $product->save();

        return redirect()->route('stock_transactions.index')
            ->with('success', 'Stok minimum berhasil diatur!');
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

        $this->stockTransactionService->createTransaction([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'type' => $request->type,
            'quantity' => $request->quantity,
            'transaction_date' => $transactionDate
        ]);

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
        $this->stockTransactionService->updateTransaction($transaction->id, $request->all());

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = StockTransaction::find($id);
        $this->stockTransactionService->deleteTransaction($transaction->id);

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus!');
    }
}
