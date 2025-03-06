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
        $transactions = $this->stockTransactionService->getAllStockTransactionsPaginated(10);
        $userRole = $this->userService->getUserRole(auth()->id());
        $products = Product::all();
        
        return view('stock_transactions.index', compact('transactions', 'userRole', 'products'));
    }

    public function create()
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }

        return view('stock_transactions.create', [
            'products' => Product::all(),
            'users' => User::all(),
            'userRole' => $userRole,
        ]);
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
    
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }
    
        Log::info('Data transaksi masuk:', $request->all());
    
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
        ]);
    
        $validatedData['status'] = 'Pending';
    
        $transaction = $this->stockTransactionService->createStockTransaction($validatedData);
    
        return $transaction
            ? redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dicatat dan menunggu persetujuan!')
            : redirect()->route('stock_transactions.index')->with('error', 'Gagal mencatat transaksi stok. Pastikan data valid dan stok mencukupi.');
    }

    public function edit($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        // Hanya Warehouse Manager yang boleh mengedit transaksi
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk mengedit transaksi stok.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        return view('stock_transactions.edit', [
            'transaction' => $transaction,
            'products' => Product::all(),
            'users' => User::all(),
            'userRole' => $userRole,
        ]);
    }

    public function update(Request $request, $id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui transaksi stok.');
        }

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
        ]);

        return $this->stockTransactionService->updateStockTransaction($id, $validatedData)
            ? redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!')
            : redirect()->route('stock_transactions.index')->with('error', 'Gagal memperbarui transaksi stok. Periksa data atau stok yang tersedia.');
    }

    public function destroy($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        
        // Hanya Warehouse Manager yang boleh menghapus transaksi
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk menghapus transaksi stok.');
        }
    
        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan.');
        }
    
        // Jika transaksi sudah disetujui, kembalikan stok sebelum dihapus
        if ($transaction->status === 'Diterima') {
            $product = Product::find($transaction->product_id);
    
            if ($transaction->type === 'Masuk') {
                $product->stock -= $transaction->quantity;
            } elseif ($transaction->type === 'Keluar') {
                $product->stock += $transaction->quantity;
            }
    
            $product->save();
        }
    
        return $this->stockTransactionService->deleteStockTransaction($id)
            ? redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus dan stok dikembalikan!')
            : redirect()->route('stock_transactions.index')->with('error', 'Gagal menghapus transaksi stok.');
    }

    public function stockOpname(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if (!in_array($userRole, ['admin', 'warehouse_manager'])) {
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


    public function confirm(Request $request, $id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_staff') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk mengonfirmasi transaksi stok.');
        }
    
        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }
    
        // Update the status of the transaction to 'Confirmed'
        $transaction->status = 'Confirmed';
        $transaction->save();
    
        return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dikonfirmasi!');
    }
    
    public function updateStatus(Request $request, $id)
{
    $userRole = $this->userService->getUserRole(auth()->id());
    if ($userRole !== 'warehouse_manager') {
        return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk mengubah status transaksi.');
    }
    
    $request->validate(['status' => 'required|in:Pending,Diterima,Ditolak']);
    
    $transaction = $this->stockTransactionService->getStockTransactionById($id);
    if (!$transaction) {
        return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan.');
    }

    if ($transaction->status !== 'Confirmed') {
        $message = $transaction->type === 'Masuk' ? 'Barang masuk belum diperiksa oleh staff.' : 'Barang keluar belum disiapkan oleh staff.';
        return redirect()->route('stock_transactions.index')->with('error', $message);
    }
    
    return $this->stockTransactionService->updateTransactionStatus($id, $request->status)
        ? redirect()->route('stock_transactions.index')->with('success', 'Status transaksi berhasil diubah!')
        : redirect()->route('stock_transactions.index')->with('error', 'Gagal mengubah status transaksi.');
}

    
}
