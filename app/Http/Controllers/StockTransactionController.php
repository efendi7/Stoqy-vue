<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\UserService;
use App\Services\StockTransactionService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\StockTransaction;

class StockTransactionController extends Controller
{
    protected $productService;
    protected $stockTransactionService;
    protected $userService;

    public function __construct(
        ProductService $productService,
        StockTransactionService $stockTransactionService,
        UserService $userService
    ) {
        $this->productService = $productService;
        $this->stockTransactionService = $stockTransactionService;
        $this->userService = $userService;
    }

  public function index(Request $request): Response
{
    $userRole = auth()->user()->role;
    $filters = $request->only('search', 'status', 'type');

    return Inertia::render('StockTransactions/Index', [
        'transactions' => $this->stockTransactionService->getAllTransactionsPaginated(10, $filters),
        'userRole' => $userRole,
        'filters' => $filters,
        'pendingTransactions' => $this->stockTransactionService->getRecentPendingTransactions(3),
        'confirmedTransactions' => $this->stockTransactionService->getRecentConfirmedTransactions(3),

        // --- TAMBAHKAN DUA BARIS INI ---
        'products' => $this->productService->getAllProducts(),
        'users' => $this->userService->getAllUsers(),
    ]);
}

    public function dashboard(): Response
{
    // Debug: Check what's in the database
    $debug = $this->stockTransactionService->debugTransactions();
    \Log::info('Debug transactions:', $debug);
    
    // Or dump to see immediately
    // dd($debug);

    return Inertia::render('Dashboard/Index', [
        'incomingTaskStaff' => $this->stockTransactionService->getPendingTransactionsByType('Masuk'),
        'outgoingTaskStaff' => $this->stockTransactionService->getPendingTransactionsByType('Keluar'),
        'completeTaskStaff' => $this->stockTransactionService->getTransactionsByStatus('Confirmed'),
        'transactions' => $this->stockTransactionService->getTransactionsByStatus('Diterima', 10),
        
        // Add debug data to view
        'debug' => $debug
    ]);
}

    public function create(): Response|\Illuminate\Http\RedirectResponse
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin.');
        }

        return Inertia::render('StockTransactions/Create', [
            'products' => $this->productService->getAllProducts(),
            'users' => $this->userService->getAllUsers(),
            'userRole' => $userRole,
        ]);
    }

    public function store(Request $request)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin.');
        }

        $validatedData = $this->stockTransactionService->validateTransactionData($request->all());
        $transaction = $this->stockTransactionService->createStockTransaction($validatedData);

        if ($transaction) {
            $this->stockTransactionService->logTransactionActivity('Menambahkan', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi berhasil dicatat!');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal mencatat transaksi.');
    }

    public function edit($id): Response|\Illuminate\Http\RedirectResponse
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Tidak memiliki izin.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        return Inertia::render('StockTransactions/Edit', [
            'transaction' => $transaction,
            'products' => $this->productService->getAllProducts(),
            'users' => $this->userService->getAllUsers(),
            'userRole' => $userRole,
        ]);
    }

    public function update(Request $request, $id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Tidak memiliki izin.');
        }

        $validatedData = $this->stockTransactionService->validateTransactionData($request->all());
        $transaction = $this->stockTransactionService->getStockTransactionById($id);

        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($this->stockTransactionService->updateStockTransaction($id, $validatedData)) {
            $this->stockTransactionService->logTransactionActivity('Memperbarui', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi diperbarui.');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal memperbarui transaksi.');
    }

    public function destroy($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Tidak memiliki izin.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);

        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak ditemukan.');
        }

        if ($this->stockTransactionService->deleteStockTransaction($id)) {
            $this->stockTransactionService->logTransactionActivity('Menghapus', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi dihapus.');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal menghapus transaksi.');
    }

    public function confirm($id)
    {
        $userRole = $this->userService->getUserRole(auth()->id());

        if ($userRole !== 'warehouse_staff') {
            return redirect()->route('stock_transactions.index')->with('error', 'Tidak memiliki izin.');
        }

        $transaction = $this->stockTransactionService->getStockTransactionById($id);

        if (!$transaction || $transaction->status !== 'Pending') {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi tidak valid.');
        }

        if ($this->stockTransactionService->confirmTransaction($transaction)) {
            $this->stockTransactionService->logTransactionActivity('Mengonfirmasi', $transaction, auth()->id());
            return redirect()->route('stock_transactions.index')->with('success', 'Transaksi dikonfirmasi.');
        }

        return redirect()->route('stock_transactions.index')->with('error', 'Gagal konfirmasi transaksi.');
    }

   public function pending(): Response
{
    // Tambahkan request untuk filter pencarian
    $filters = request()->only('search');

    return Inertia::render('StockTransactions/Pending', [
        // Ganti nama prop dan buat query lebih efisien
        'transactions' => StockTransaction::with(['product', 'user'])
            ->where('status', 'pending')
            ->filter($filters) // Asumsi Anda punya scope filter
            ->paginate(10)
            ->withQueryString(),
        'filters' => $filters,
    ]);
}

public function confirmed(): Response
{
    $filters = request()->only('search');

    return Inertia::render('StockTransactions/Confirmed', [
        // Ganti nama prop dan buat query lebih efisien
        'transactions' => StockTransaction::with(['product', 'user'])
            ->whereIn('status', ['Diterima', 'Ditolak']) // Sesuaikan status
            ->filter($filters) // Asumsi Anda punya scope filter
            ->paginate(10)
            ->withQueryString(),
        'filters' => $filters,
    ]);
}

    public function updateStatus(Request $request, $id)
    {
        $transaction = StockTransaction::findOrFail($id);
        $transaction->status = $request->input('status');
        $transaction->save();

        return redirect()->route('stock_transactions.index')->with('success', 'Status berhasil diperbarui.');
    }
     public function show(StockTransaction $stockTransaction): Response
    {
        // Load related data to avoid extra database queries in the view
        $stockTransaction->load('product', 'user', 'activities.user');

        return Inertia::render('StockTransactions/Show', [
            'transaction' => $stockTransaction,
        ]);
    }
}
