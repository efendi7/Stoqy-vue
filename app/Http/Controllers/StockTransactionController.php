<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\UserService;
use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\User;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog;


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
        $user = auth()->user();
        $userRole = $user->role;
    
        $pendingTransactions = StockTransaction::where('status', 'Pending')
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    $confirmedTransactions = StockTransaction::whereIn('status', ['Confirmed', 'Diterima', 'Ditolak'])
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();
    
    
        // Menampilkan semua transaksi tanpa memfilter status
        $transactions = StockTransaction::latest('transaction_date')->paginate(10);
    
        return view('stock_transactions.index', compact(
            'transactions', 'userRole', 'pendingTransactions', 'confirmedTransactions'
        ));
    }
    

    public function dashboard()
    {
        $user = auth()->user();
        
        // Tugas baru untuk staff: Barang masuk pending
        $incomingTaskStaff = StockTransaction::where('status', 'Pending')
            ->where('type', 'Masuk')
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Tugas baru untuk staff: Barang keluar pending
        $outgoingTaskStaff = StockTransaction::where('status', 'Pending')
            ->where('type', 'Keluar')
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Tugas yang telah dikonfirmasi staff
        $completeTaskStaff = StockTransaction::where('status', 'Confirmed')
            ->orderBy('created_at', 'desc')
            ->get();
    
        // Transaksi yang telah diterima
        $transactions = StockTransaction::where('status', 'Diterima')
            ->latest('transaction_date')
            ->paginate(10);
    
        return view('dashboard.index', compact(
            'incomingTaskStaff', 
            'outgoingTaskStaff', 
            'completeTaskStaff', 
            'transactions'
        ));
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
        
        // Check if the user has the appropriate role
        if ($userRole !== 'warehouse_manager') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk membuat transaksi stok.');
        }
    
        // Log incoming request data
        Log::info('Data transaksi masuk:', $request->all());
    
        // Validate input data
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);
    
        // Set the default status for the transaction
        $validatedData['status'] = 'Pending';
    
        // Create the stock transaction
        $transaction = $this->stockTransactionService->createStockTransaction($validatedData);
    
        // Log activity if the transaction is successfully created
        if ($transaction) {
            // Fetch the product name for the activity log
            $productName = $transaction->product->name ?? 'Produk Tidak Diketahui';
    
            // Create an activity log entry with detailed action description
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role,
                'action' => "Menambahkan transaksi {$validatedData['type']} {$productName} sebanyak {$validatedData['quantity']} buah",
                'properties' => json_encode([
                    'transaction_id' => $transaction->id,
                    'type' => $validatedData['type'],
                    'quantity' => $validatedData['quantity'],
                    'notes' => $validatedData['notes'] ?? null,
                ]),
            ]);
        }
    
        // Redirect based on the result of transaction creation
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

    // Check if the user has permission
    if ($userRole !== 'warehouse_manager') {
        return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk memperbarui transaksi stok.');
    }

    // Validate the incoming request data
    $validatedData = $request->validate([
        'product_id' => 'required|exists:products,id',
        'user_id' => 'required|exists:users,id',
        'type' => 'required|in:Masuk,Keluar',
        'quantity' => 'required|integer|min:1',
        'transaction_date' => 'required|date',
        'notes' => 'nullable|string'
    ]);

    // Fetch the existing transaction
    $transaction = $this->stockTransactionService->getStockTransactionById($id);
    if (!$transaction) {
        return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
    }

    // Log the original data before the update
    $oldData = $transaction->toArray();

    // Update the transaction
    $isUpdated = $this->stockTransactionService->updateStockTransaction($id, $validatedData);

    // Log activity if the transaction was updated successfully
    if ($isUpdated) {
        // Fetch the product name for logging
        $productName = $transaction->product->name ?? 'Produk Tidak Diketahui';

        // Log the activity with detailed information about the changes
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "Memperbarui transaksi {$validatedData['type']} {$productName} sebanyak {$validatedData['quantity']} buah",
            'properties' => json_encode([
                'transaction_id' => $transaction->id,
                'before' => $oldData,
                'after' => $validatedData,
            ]),
        ]);
    }

    // Redirect based on the result
    return $isUpdated
        ? redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil diperbarui!')
        : redirect()->route('stock_transactions.index')->with('error', 'Gagal memperbarui transaksi stok. Periksa data atau stok yang tersedia.');
}
public function destroy($id)
{
    $userRole = $this->userService->getUserRole(auth()->id());

    // Ensure only Warehouse Manager can delete a transaction
    if ($userRole !== 'warehouse_manager') {
        return redirect()->route('stock_transactions.index')
            ->with('error', 'Anda tidak memiliki izin untuk menghapus transaksi stok.');
    }

    // Fetch the transaction by ID
    $transaction = $this->stockTransactionService->getStockTransactionById($id);
    if (!$transaction) {
        return redirect()->route('stock_transactions.index')
            ->with('error', 'Transaksi tidak ditemukan.');
    }

    // Store old transaction data for logging
    $oldData = $transaction->toArray();

    // If transaction is approved, revert stock changes
    if ($transaction->status === 'Diterima') {
        $product = $this->productService->getProductById($transaction->product_id);

        if ($product) {
            if ($transaction->type === 'Masuk') {
                $product->stock = max(0, $product->stock - $transaction->quantity);
            } elseif ($transaction->type === 'Keluar') {
                $product->stock += $transaction->quantity;
            }

            $this->productService->updateProductStock($product->id, $product->stock);
        }
    }

    // Delete the transaction
    $isDeleted = $this->stockTransactionService->deleteStockTransaction($id);

    // Log the deletion activity
    if ($isDeleted) {
        $productName = $transaction->product->name ?? 'Produk Tidak Diketahui';

        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "Menghapus transaksi {$transaction->type} {$productName} sebanyak {$transaction->quantity} buah",
            'properties' => json_encode([
                'transaction_id' => $transaction->id,
                'transaction_status' => $transaction->status,
                'affected_stock' => $transaction->quantity,
                'product_stock_after' => $product->stock ?? 'Tidak Diketahui',
                'transaction_details' => $oldData,
            ]),
        ]);
    }

    // Redirect based on the outcome
    return $isDeleted
        ? redirect()->route('stock_transactions.index')->with('success', 'Transaksi stok berhasil dihapus dan stok dikembalikan!')
        : redirect()->route('stock_transactions.index')->with('error', 'Gagal menghapus transaksi stok.');
}


    public function confirm(Request $request, $id)
    {
        // Check if the user has the warehouse_staff role
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_staff') {
            return redirect()->route('stock_transactions.index')
                ->with('error', 'Anda tidak memiliki izin untuk mengonfirmasi transaksi stok.');
        }
    
        // Retrieve the transaction using the service
        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')
                ->with('error', 'Transaksi stok tidak ditemukan.');
        }
    
        // Log the original transaction data before confirmation
        $oldData = $transaction->toArray();
    
        // Update the status to "Confirmed"
        $transaction->status = 'Confirmed';
    
        // Save a note if provided
        if ($request->has('note')) {
            $transaction->note = $request->note;
        }
    
        // Save the changes
        $transaction->save();
    
        // Fetch the product name for activity logging
        $productName = $transaction->product->name ?? 'Produk Tidak Diketahui';
    
        // Log the confirmation activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "Memeriksa dan Mengonfirmasi transaksi {$transaction->type} {$productName} sebanyak {$transaction->quantity} buah",
            'properties' => json_encode([
                'transaction_id' => $transaction->id,
                'before' => $oldData,
                'after' => $transaction->toArray(),
            ]),
        ]);
    
        // Redirect to the index route with a success message
        return redirect()->route('stock_transactions.index')
            ->with('success', 'Transaksi stok berhasil dikonfirmasi!');
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
        $message = $transaction->type === 'Masuk' 
            ? 'Barang masuk belum diperiksa oleh staff.' 
            : 'Barang keluar belum disiapkan oleh staff.';
        return redirect()->route('stock_transactions.index')->with('error', $message);
    }

    $oldStatus = $transaction->status;
    $isUpdated = $this->stockTransactionService->updateTransactionStatus($id, $request->status);

    if ($isUpdated) {
        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => "Mengubah status transaksi {$transaction->type} menjadi {$request->status}",
            'properties' => json_encode([
                'transaction_id' => $transaction->id,
                'previous_status' => $oldStatus,
                'new_status' => $request->status,
            ]),
        ]);
    }

    return $isUpdated
        ? redirect()->route('stock_transactions.index')->with('success', 'Status transaksi berhasil diubah!')
        : redirect()->route('stock_transactions.index')->with('error', 'Gagal mengubah status transaksi.');
}

public function pending()
{
    $pendingTransactions = StockTransaction::where('status', 'Pending')
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Log activity for viewing pending transactions
    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'action' => 'Melihat daftar transaksi dengan status Pending',
        'properties' => null,
    ]);

    return view('stock_transactions.pending', compact('pendingTransactions'));
}

public function confirmed()
{
    $confirmedTransactions = StockTransaction::whereIn('status', ['Confirmed', 'Diterima', 'Ditolak'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    // Log activity for viewing confirmed transactions
    ActivityLog::create([
        'user_id' => auth()->id(),
        'role' => auth()->user()->role,
        'action' => 'Melihat daftar transaksi yang telah dikonfirmasi',
        'properties' => null,
    ]);

    return view('stock_transactions.confirmed', compact('confirmedTransactions'));
}

public function confirmTransaction(Request $request, $id)
{
    $transaction = StockTransaction::findOrFail($id);
    
    // Pastikan jika ada catatan baru dari input, maka diperbarui
    if ($request->has('note')) {
        $transaction->note = $request->note;
    }

    $transaction->status = 'Confirmed'; // Atau sesuaikan status lain jika diperlukan
    $transaction->save();

    return back()->with('success', 'Transaksi berhasil dikonfirmasi!');
}


}
