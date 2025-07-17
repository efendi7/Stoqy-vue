<?php

namespace App\Services;

use App\Repositories\StockTransactionRepository;
use App\Models\Product;
use App\Models\TransactionLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Validator;

class StockTransactionService
{
    protected $stockTransactionRepository;

    public function __construct(StockTransactionRepository $stockTransactionRepository)
    {
        $this->stockTransactionRepository = $stockTransactionRepository;
    }

    public function getAllTransactionsPaginated($perPage = 10, $filters = [])
    {
        return $this->stockTransactionRepository->getAllTransactionsPaginated($perPage, $filters);
    }

    public function getStockTransactionById($id)
    {
        return $this->stockTransactionRepository->findById($id);
    }

    // ADD THIS METHOD - Missing from your code
    public function getPendingTransactionsByType($type)
    {
        return StockTransaction::with(['product', 'user'])
            ->where('type', $type)
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ADD THIS METHOD - Missing from your code
    public function getTransactionsByStatus($status, $limit = null)
    {
        $query = StockTransaction::with(['product', 'user'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc');

        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }

    public function createStockTransaction($data)
    {
        DB::beginTransaction();
        try {
            $transaction = $this->stockTransactionRepository->create($data);
            $this->updateProductStock($transaction->product_id, $transaction->quantity, $transaction->transaction_type);
            
            // Get product name for logging
            $productName = Product::find($transaction->product_id)->name ?? 'Unknown Product';
            
            // Log activity for creation
            $this->logActivity("Menambahkan transaksi stok: {$transaction->id} - Produk: {$productName}", $transaction);
            
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create stock transaction: ' . $e->getMessage());
            return null;
        }
    }

    public function updateStockTransaction($id, $data)
    {
        DB::beginTransaction();
        try {
            $transaction = $this->getStockTransactionById($id);
            if (!$transaction) return null;

            // Store old data for logging
            $oldData = $transaction->toArray();

            // Get product name for logging
            $productName = Product::find($transaction->product_id)->name ?? 'Unknown Product';

            // Rollback stock before update
            $this->rollbackProductStock($transaction->product_id, $transaction->quantity, $transaction->transaction_type);

            $transaction = $this->stockTransactionRepository->update($id, $data);

            // Apply new stock change
            $this->updateProductStock($transaction->product_id, $transaction->quantity, $transaction->transaction_type);
            
            // Log activity for update
            $this->logActivity("Memperbarui transaksi stok: {$transaction->id} - Produk: {$productName}", $transaction, $oldData);

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update stock transaction: ' . $e->getMessage());
            return null;
        }
    }

    public function deleteStockTransaction($id)
    {
        DB::beginTransaction();
        try {
            $transaction = $this->getStockTransactionById($id);
            if (!$transaction) return false;

            // Store old data for logging
            $oldData = $transaction->toArray();

            // Get product name for logging
            $productName = Product::find($transaction->product_id)->name ?? 'Unknown Product';

            // Log activity for deletion **SEBELUM transaksi dihapus**
            $this->logActivity("Menghapus transaksi stok: {$transaction->id} - Produk: {$productName}", $transaction, $oldData);
            
            // Log transaction activity juga **SEBELUM transaksi dihapus**
            $this->logTransactionActivity("Menghapus", $transaction, auth()->id());

            // Rollback stock when deleting transaction
            $this->rollbackProductStock($transaction->product_id, $transaction->quantity, $transaction->transaction_type);

            $this->stockTransactionRepository->delete($id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete stock transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function confirmTransaction($transaction)
    {
        DB::beginTransaction();
        try {
            // Store old data for logging
            $oldData = $transaction->toArray();
            
            // Get product name for logging
            $productName = Product::find($transaction->product_id)->name ?? 'Unknown Product';
            
            $transaction->update(['status' => 'Confirmed']);
            
            // Log activity for confirmation
            $this->logActivity("Mengkonfirmasi transaksi stok: {$transaction->id} - Produk: {$productName}", $transaction, $oldData);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to confirm stock transaction: ' . $e->getMessage());
            return false;
        }
    }

    private function updateProductStock($productId, $quantity, $transactionType)
    {
        $product = Product::find($productId);
        if (!$product) {
            throw new \Exception("Produk tidak ditemukan.");
        }

        if ($transactionType === 'In') {
            $product->stock += $quantity;
        } else {
            if ($product->stock < $quantity) {
                throw new \Exception("Stok tidak mencukupi.");
            }
            $product->stock -= $quantity;
        }

        $product->save();
    }

    private function rollbackProductStock($productId, $quantity, $transactionType)
    {
        $product = Product::find($productId);
        if (!$product) {
            throw new \Exception("Produk tidak ditemukan.");
        }

        if ($transactionType === 'In') {
            $product->stock -= $quantity;
        } else {
            $product->stock += $quantity;
        }

        $product->save();
    }

    public function logTransactionActivity($action, $transaction, $userId)
    {
        // Cek apakah transaksi masih ada di database
        if (!StockTransaction::find($transaction->id)) {
            Log::warning("Gagal mencatat log transaksi: Transaksi ID {$transaction->id} sudah tidak ada.");
            return;
        }
    
        // Get product name
        $productName = Product::find($transaction->product_id)->name ?? 'Unknown Product';
    
        TransactionLog::create([
            'user_id' => $userId,
            'transaction_id' => $transaction->id,
            'action' => $action,
            'description' => "$action transaksi stok ID: {$transaction->id} - Produk: {$productName}",
        ]);
    }
    
    // Log aktivitas seperti di ProductService
    public function logActivity($action, $transaction, $oldData = null)
    {
        // Get product name
        $productName = Product::find($transaction->product_id)->name ?? 'Unknown Product';
        
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => $action,
            'properties' => json_encode([
                'transaction_id' => $transaction->id,
                'product_id' => $transaction->product_id,
                'product_name' => $productName,
                'transaction_type' => $transaction->transaction_type,
                'quantity' => $transaction->quantity,
                'before' => $oldData,
                'after' => $transaction->toArray(),
            ]),
        ]);
    }

    // FIXED: More efficient method
    public function getRecentPendingTransactions($limit = 3)
    {
        return StockTransaction::with(['product', 'user'])
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    // FIXED: More efficient method
    public function getRecentConfirmedTransactions($limit = 3)
    {
        return StockTransaction::with(['product', 'user'])
            ->whereIn('status', ['Confirmed', 'Diterima', 'Ditolak'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function validateTransactionData($data)
    {
        return Validator::make($data, [
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:Masuk,Keluar',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ])->validate();
    }

    // ADD THIS METHOD for debugging
    public function debugTransactions()
    {
        $allTransactions = StockTransaction::all();
        $pendingTransactions = StockTransaction::where('status', 'Pending')->get();
        $keluarTransactions = StockTransaction::where('type', 'Keluar')->get();
        $pendingKeluarTransactions = StockTransaction::where('type', 'Keluar')
            ->where('status', 'Pending')
            ->get();

        return [
            'total_transactions' => $allTransactions->count(),
            'pending_transactions' => $pendingTransactions->count(),
            'keluar_transactions' => $keluarTransactions->count(),
            'pending_keluar_transactions' => $pendingKeluarTransactions->count(),
            'pending_keluar_data' => $pendingKeluarTransactions->toArray()
        ];
    }
}