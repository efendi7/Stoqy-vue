<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransactionService
{
    /**
     * Get all stock transactions with pagination.
     */
    public function getAllStockTransactionsPaginated($perPage = 10): LengthAwarePaginator
    {
        return StockTransaction::with(['product', 'user'])->latest()->paginate($perPage);
    }

    /**
     * Get a single stock transaction by ID.
     */
    public function getStockTransactionById($id): StockTransaction
    {
        return StockTransaction::with(['product', 'user'])->findOrFail($id);
    }

    /**
     * Create a new stock transaction.
     */
    public function createStockTransaction(array $data): StockTransaction|bool
    {
        DB::beginTransaction();
        try {
            $transaction = StockTransaction::create($data);
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating stock transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing stock transaction.
     */
    public function updateStockTransaction($id, array $data): bool
    {
        $transaction = StockTransaction::find($id);
        if (!$transaction) {
            return false;
        }

        DB::beginTransaction();
        try {
            $transaction->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating stock transaction: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a stock transaction and revert stock if necessary.
     */
    public function deleteStockTransaction($id): bool
    {
        $transaction = StockTransaction::find($id);
        if (!$transaction) {
            return false;
        }

        if ($transaction->status === 'Diterima') {
            $this->handleStockUpdate($transaction, 'revert');
        }

        $transaction->delete();
        return true;
    }

    /**
     * Update stock transaction status and handle stock changes.
     */
    public function updateTransactionStatus($id, $newStatus): bool
    {
        $transaction = StockTransaction::find($id);
        if (!$transaction) {
            return false;
        }

        DB::beginTransaction();
        try {
            $oldStatus = $transaction->status;
            $transaction->status = $newStatus;
            $transaction->save();

            if ($oldStatus !== 'Diterima' && $newStatus === 'Diterima') {
                $this->handleStockUpdate($transaction, 'process');
            } elseif ($oldStatus === 'Diterima' && $newStatus !== 'Diterima') {
                $this->handleStockUpdate($transaction, 'revert');
            }

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating transaction status: ' . $e->getMessage());
            return false;
        }
    }

    public function getStockTransactionsByDateAndStatus($date, $status)
{
    return StockTransaction::whereDate('transaction_date', $date)
        ->where('status', $status)
        ->get();
}


    /**
     * Process or revert stock updates based on transaction type.
     */
    private function handleStockUpdate(StockTransaction $transaction, string $action)
{
    $product = Product::find($transaction->product_id);
    if (!$product) {
        throw new \Exception('Product not found.');
    }

    Log::info('Sebelum update stok', [
        'product_id' => $product->id,
        'stok_sekarang' => $product->stock,
        'jumlah_transaksi' => $transaction->quantity,
        'aksi' => $action
    ]);

    if ($transaction->type === 'Masuk') {
        $amount = $transaction->quantity;
        if ($action === 'revert' && $product->stock < $amount) {
            Log::warning("Stock not enough to revert. Product ID: {$product->id}, Current Stock: {$product->stock}, Amount: {$amount}");
            return; // Jangan throw error, cukup log dan lanjutkan penghapusan
        }
        
        $product->stock += ($action === 'process') ? $amount : -$amount;
    } else { // type 'Keluar'
        $amount = $transaction->quantity;
        if ($action === 'process' && $product->stock < $amount) {
            throw new \Exception('Insufficient stock.');
        }
        $product->stock -= ($action === 'process') ? $amount : -$amount;
    }

    $product->save();

    Log::info('Setelah update stok', [
        'product_id' => $product->id,
        'stok_baru' => $product->stock
    ]);
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

    // Hapus logika update stok di sini, karena sudah dikelola oleh service
    return $this->stockTransactionService->updateTransactionStatus($id, $request->status)
        ? redirect()->route('stock_transactions.index')->with('success', 'Status transaksi berhasil diubah!')
        : redirect()->route('stock_transactions.index')->with('error', 'Gagal mengubah status transaksi.');
}

}
