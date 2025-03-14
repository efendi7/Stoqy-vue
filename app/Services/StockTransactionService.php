<?php

namespace App\Services;

use App\Repositories\StockTransactionRepository;
use App\Models\Product;
use App\Models\TransactionLog;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransactionService
{
    protected $stockTransactionRepository;

    public function __construct(StockTransactionRepository $stockTransactionRepository)
    {
        $this->stockTransactionRepository = $stockTransactionRepository;
    }

    public function getAllTransactionsPaginated($perPage = 10)
    {
        return $this->stockTransactionRepository->getAllTransactionsPaginated($perPage);
    }

    public function getStockTransactionById($id)
    {
        return $this->stockTransactionRepository->findById($id);
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

            // Rollback stock when deleting transaction
            $this->rollbackProductStock($transaction->product_id, $transaction->quantity, $transaction->transaction_type);

            $this->stockTransactionRepository->delete($id);
            
            // Log activity for deletion
            $this->logActivity("Menghapus transaksi stok: {$transaction->id} - Produk: {$productName}", $transaction, $oldData);
            
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

    public function getRecentPendingTransactions($limit = 3)
    {
        return $this->stockTransactionRepository
            ->getAllTransactionsPaginated($limit)
            ->filter(fn($transaction) => $transaction->status === 'Pending');
    }
    
    public function getRecentConfirmedTransactions($limit = 3)
    {
        return $this->stockTransactionRepository
            ->getAllTransactionsPaginated($limit)
            ->filter(fn($transaction) => in_array($transaction->status, ['Confirmed', 'Diterima', 'Ditolak']));
    }
}