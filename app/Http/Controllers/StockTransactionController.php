<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\UserService;
use App\Services\StockTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;
use App\Models\StockTransaction;


class StockTransactionController extends Controller
{
    private $productService;
    protected $stockTransactionService;
    protected $userService;

    public function __construct(ProductService $productService, StockTransactionService $stockTransactionService, UserService $userService)
    {
        $this->productService = $productService;
        $this->stockTransactionService = $stockTransactionService;
        $this->userService = $userService;
    }

    public function index()
    {
        $userRole = auth()->user()->role;
        
        $pendingTransactions = $this->stockTransactionService->getRecentPendingTransactions(3);
        $confirmedTransactions = $this->stockTransactionService->getRecentConfirmedTransactions(3);
        $transactions = $this->stockTransactionService->getAllTransactionsPaginated(10);

        return view('stock_transactions.index', compact(
            'transactions', 'userRole', 'pendingTransactions', 'confirmedTransactions'
        ));
    }

    public function dashboard()
    {
        $incomingTaskStaff = $this->stockTransactionService->getPendingTransactionsByType('Masuk');
        $outgoingTaskStaff = $this->stockTransactionService->getPendingTransactionsByType('Keluar');
        $completeTaskStaff = $this->stockTransactionService->getTransactionsByStatus('Confirmed');
        $transactions = $this->stockTransactionService->getTransactionsByStatus('Diterima', 10);

        return view('dashboard.index', compact(
            'incomingTaskStaff', 'outgoingTaskStaff', 'completeTaskStaff', 'transactions'
        ));
    }

    public function create()
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }

        $products = $this->productService->getAllProducts();
        $users = $this->userService->getAllUsers();

        return view('stock_transactions.create', compact('products', 'users', 'userRole'));
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }

        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $validatedData['status'] = 'Pending';

        $transaction = $this->stockTransactionService->createStockTransaction($validatedData);

        if ($transaction) {
            $this->stockTransactionService->logTransactionActivity('Menambahkan', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dicatat dan menunggu persetujuan!');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal mencatat transaksi stok.');
    }

    public function edit($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk mengedit transaksi stok.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        $products = $this->productService->getAllProducts();
        $users = $this->userService->getAllUsers();

        return view('stock_transactions.edit', compact('transaction', 'products', 'users', 'userRole'));
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
            'notes' => 'nullable|string'
        ]);

        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        $isUpdated = $this->stockTransactionService->updateStockTransaction($id, $validatedData);
        if ($isUpdated) {
            $this->stockTransactionService->logTransactionActivity('Memperbarui', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal memperbarui     transaksi stok.');
    }

    public function destroy($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk menghapus transaksi stok.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }

        $isDeleted = $this->stockTransactionService->deleteStockTransaction($id);
        if ($isDeleted) {
            $this->stockTransactionService->logTransactionActivity('Menghapus', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus!');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal menghapus transaksi stok.');
    }

    public function confirm($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_staff') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk mengonfirmasi transaksi stok.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction || $transaction->status !== 'Pending') {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan atau sudah dikonfirmasi.');
        }

        $isConfirmed = $this->stockTransactionService->confirmTransaction($transaction);
        if ($isConfirmed) {
            $this->stockTransactionService->logTransactionActivity('Mengonfirmasi', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi berhasil dikonfirmasi!');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal mengonfirmasi transaksi.');
    }

    public function pending()
    {
    $pendingTransactions = StockTransaction::where('status', 'pending')->paginate(10);
    return view('stock_transactions.pending', compact('pendingTransactions'));
    }

    public function confirmed()
    {
    $confirmedTransactions = StockTransaction::whereIn('status', ['confirmed', 'diterima', 'ditolak'])->paginate(10);
    return view('stock_transactions.confirmed', compact('confirmedTransactions'));
    }

    public function updateStatus(Request $request, $id)
    {
    $transaction = StockTransaction::findOrFail($id);
    $transaction->status = $request->input('status');
    $transaction->save();

    return redirect()->route('stock_transactions.index')->with('success', 'Status berhasil diperbarui');
    }
}

