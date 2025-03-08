<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\StockTransactionService;
use App\Models\Product;
use App\Models\User;
use App\Models\StockTransaction;
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
        $transactions = StockTransaction::where('status', 'Diterima')
            ->latest('transaction_date')
            ->paginate(10);
    
        return view('dashboard.index', compact('transactions'));
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
            'notes' => 'nullable|string'
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
            'notes' => 'nullable|string'
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
            return redirect()->route('stock_transactions.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus transaksi stok.');
        }
    
        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }
    
        // Jika transaksi sudah disetujui, kembalikan stok sebelum dihapus
        if ($transaction->status === 'Diterima') {
            $product = $this->productService->getProductById($transaction->product_id); // Gunakan service
    
            if ($product) {
                if ($transaction->type === 'Masuk') {
                    $product->stock = max(0, $product->stock - $transaction->quantity);
                } elseif ($transaction->type === 'Keluar') {
                    $product->stock += $transaction->quantity;
                }
    
                $this->productService->updateProductStock($product->id, $product->stock); // Update lewat service
            }
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

    // Add the confirm method here
    public function confirm(Request $request, $id)
    {
        // Cek apakah pengguna adalah warehouse_staff
        $userRole = $this->userService->getUserRole(auth()->id());
        if ($userRole !== 'warehouse_staff') {
            return redirect()->route('stock_transactions.index')->with('error', 'Anda tidak memiliki izin untuk mengonfirmasi transaksi stok.');
        }
    
        // Ambil data transaksi dari service
        $transaction = $this->stockTransactionService->getStockTransactionById($id);
        if (!$transaction) {
            return redirect()->route('stock_transactions.index')->with('error', 'Transaksi stok tidak ditemukan.');
        }
    
        // Update status menjadi "Confirmed"
        $transaction->status = 'Confirmed';
    
        // Simpan catatan jika ada input dari request
        if ($request->has('note')) {
            $transaction->note = $request->note;
        }
    
        // Simpan perubahan
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

    public function pending()
{
    $pendingTransactions = StockTransaction::where('status', 'Pending')
        ->orderBy('created_at', 'desc')
        ->paginate(10); // Pakai pagination

    return view('stock_transactions.pending', compact('pendingTransactions'));
}

public function confirmed()
{
    $confirmedTransactions = StockTransaction::whereIn('status', ['Confirmed', 'Diterima', 'Ditolak'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('stock_transactions.confirmed', compact('confirmedTransactions'));
}

public function addNote(Request $request, $id) {
    $transaction = StockTransaction::findOrFail($id);
    $transaction->note = $request->note;
    $transaction->save();

    return back()->with('success', 'Catatan berhasil ditambahkan.');
}
public function show($id)
{
    $stockTransaction = StockTransaction::findOrFail($id);
    return view('stock_transactions.show', compact('stockTransaction'));
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
