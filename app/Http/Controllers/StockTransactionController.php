<?php

namespace App\Http\Controllers;

use App\Services\UserService; // Import UserService
use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\User;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockTransactionController extends Controller
{
    protected $stockTransactionService;
    protected $userService;

    public function __construct(StockTransactionService $stockTransactionService, UserService $userService)
    {
        $this->stockTransactionService = $stockTransactionService;
        $this->userService = $userService;
    }

    public function index()
    {
        $transactions = $this->stockTransactionService->getAllTransactions();
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        return view('stock_transactions.index', compact('transactions', 'userRole'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        return view('stock_transactions.create', compact('products', 'users', 'userRole'));
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        
        if (!in_array($userRole, ['Admin', 'Manajer Gudang'])) {
            return redirect()->route('stock_transactions.index')
                ->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }

        \Log::info('Data yang diterima di metode store: ', $request->all());

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
        ]);

        $this->stockTransactionService->createTransaction($request->all());

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dicatat!');
    }

    public function edit($id)
    {
        $transaction = StockTransaction::find($id);
        $products = Product::all();
        $users = User::all();
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        return view('stock_transactions.edit', compact('transaction', 'products', 'users', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $userRole = $this->userService->getUserRole(auth()->id()); // Get user role
        
        if (!in_array($userRole, ['Admin', 'Manajer Gudang'])) {
            return redirect()->route('stock_transactions.index')
                ->with('error', 'Anda tidak memiliki izin untuk memperbarui transaksi stok.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ]);

        $this->stockTransactionService->updateTransaction($id, $request->all());

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->stockTransactionService->deleteTransaction($id);
        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus!');
    }
}
