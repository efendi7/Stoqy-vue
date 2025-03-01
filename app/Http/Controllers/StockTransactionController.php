<?php 

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $transactions = $this->stockTransactionService->getAllStockTransactions();
        $userRole = $this->userService->getUserRole(auth()->id());
        $products = Product::all();
        
        return view('stock_transactions.index', compact('transactions', 'userRole', 'products'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        $userRole = $this->userService->getUserRole(auth()->id());

        return view('stock_transactions.create', compact('products', 'users', 'userRole'));
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
    
        if (!in_array($userRole, ['admin', 'Manajer Gudang'])) {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }
    
        Log::info('Data yang diterima di metode store:', $request->all());
    
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
        ]);
    
        // Menetapkan status default ke 'Pending'
        $validatedData['status'] = 'Pending';
    
        $transaction = $this->stockTransactionService->createStockTransaction($validatedData);
    
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Gagal mencatat transaksi stok. Pastikan data valid dan stok mencukupi.');
        }
    
        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dicatat!');
    }
    
    public function edit($id)
    {
        $transaction = $this->stockTransactionService->getStockTransactionById($id);

        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        $products = Product::all();
        $users = User::all();
        $userRole = $this->userService->getUserRole(auth()->id());

        return view('stock_transactions.edit', compact('transaction', 'products', 'users', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if (!in_array($userRole, ['admin', 'Manajer Gudang'])) {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui transaksi stok.');
        }

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ]);

        $updated = $this->stockTransactionService->updateStockTransaction($id, $validatedData);

        if (!$updated) {
            return redirect()->route('stock_transactions.index')->with('error', 'Gagal memperbarui transaksi stok. Periksa data atau stok yang tersedia.');
        }

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        $deleted = $this->stockTransactionService->deleteStockTransaction($id);

        if (!$deleted) {
            return redirect()->route('stock_transactions.index')->with('error', 'Gagal menghapus transaksi stok.');
        }

        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus!');
    }

    public function stockOpname(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if (!in_array($userRole, ['admin', 'Manajer Gudang'])) {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk melakukan stock opname.');
        }

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'new_stock' => 'required|integer|min:0',
        ]);

        $product = Product::find($validatedData['product_id']);
        $product->stock = $validatedData['new_stock'];
        $product->save();

        return redirect()->route('stock_transactions.index')->with('success', 'Stock opname berhasil diperbarui!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Diterima,Ditolak',
        ]);

        $transaction = $this->stockTransactionService->getStockTransactionById($id);

        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('stock_transactions.index')->with('success', 'Status transaksi berhasil diperbarui.');
    }
}
