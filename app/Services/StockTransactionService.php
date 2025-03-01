<?php

namespace App\Services;

use App\Models\StockTransaction;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransactionService
{
    public function getAllStockTransactions()
    {
        return StockTransaction::with(['product', 'user'])->latest()->get();
    }

    public function getStockTransactionById($id)
    {
        return StockTransaction::find($id);
    }

    public function createStockTransaction(array $data)
    {
        DB::beginTransaction();
        try {
            // Buat transaksi dengan status 'Pending' dan tidak mempengaruhi stok
            $transaction = StockTransaction::create($data);
            
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating stock transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function updateStockTransaction($id, array $data)
    {
        $transaction = StockTransaction::find($id);
        
        if (!$transaction) {
            return false;
        }

        DB::beginTransaction();
        try {
            // Update transaksi tanpa mengubah status atau stok
            $transaction->update($data);
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating stock transaction: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteStockTransaction($id)
    {
        $transaction = StockTransaction::find($id);
        
        if (!$transaction) {
            return false;
        }

        // Jika transaksi sudah diterima dan mempengaruhi stok, kembalikan stok ke semula
        if ($transaction->status === 'Diterima') {
            $product = Product::find($transaction->product_id);
            
            if ($transaction->type === 'Masuk') {
                $product->stock -= $transaction->quantity;
            } else { // Keluar
                $product->stock += $transaction->quantity;
            }
            
            $product->save();
        }

        $transaction->delete();
        return true;
    }

    public function updateTransactionStatus($id, $newStatus)
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
            
            // Jika status berubah menjadi 'Diterima', update stok produk
            if ($oldStatus !== 'Diterima' && $newStatus === 'Diterima') {
                $this->processApprovedTransaction($transaction);
            }
            // Jika status berubah dari 'Diterima' ke status lain, kembalikan stok
            else if ($oldStatus === 'Diterima' && $newStatus !== 'Diterima') {
                $this->revertApprovedTransaction($transaction);
            }
            
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating transaction status: ' . $e->getMessage());
            return false;
        }
    }

    private function processApprovedTransaction($transaction)
    {
        $product = Product::find($transaction->product_id);
        
        if ($transaction->type === 'Masuk') {
            $product->stock += $transaction->quantity;
        } else { // Keluar
            // Cek apakah stok cukup
            if ($product->stock < $transaction->quantity) {
                throw new \Exception('Stok tidak mencukupi untuk transaksi keluar');
            }
            
            $product->stock -= $transaction->quantity;
        }
        
        $product->save();
    }

    private function revertApprovedTransaction($transaction)
    {
        $product = Product::find($transaction->product_id);
        
        if ($transaction->type === 'Masuk') {
            // Cek apakah stok cukup untuk dikurangi
            if ($product->stock < $transaction->quantity) {
                throw new \Exception('Stok tidak mencukupi untuk mengembalikan transaksi masuk');
            }
            
            $product->stock -= $transaction->quantity;
        } else { // Keluar
            $product->stock += $transaction->quantity;
        }
        
        $product->save();
    }
}