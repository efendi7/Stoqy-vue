<?php

namespace App\Services;

use App\Interfaces\StockTransactionRepositoryInterface;
use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Log;

class StockTransactionService
{
    protected $stockTransactionRepository;

    public function __construct(StockTransactionRepositoryInterface $stockTransactionRepository)
    {
        $this->stockTransactionRepository = $stockTransactionRepository;
    }

    public function getAllStockTransactions()
    {
        return $this->stockTransactionRepository->getAllStockTransactions();
    }

    public function getStockTransactionById($id)
{
    return $this->stockTransactionRepository->getStockTransactionById($id);
}


    public function createStockTransaction(array $data)
    {
        // Cek apakah produk ada
        $product = Product::find($data['product_id']);
        if (!$product) {
            Log::error("Produk dengan ID {$data['product_id']} tidak ditemukan.");
            return false;
        }

        // Buat transaksi stok
        $transaction = $this->stockTransactionRepository->createStockTransaction($data);

        // Update stok produk
        if ($data['type'] == 'Masuk') {
            $product->stock += $data['quantity'];
        } else {
            if ($product->stock < $data['quantity']) {
                Log::error("Stok tidak mencukupi untuk produk ID {$data['product_id']}");
                return false;
            }
            $product->stock -= $data['quantity'];
        }
        $product->save();

        Log::info("Transaksi stok berhasil dicatat: ", $transaction->toArray());

        return $transaction;
    }

    public function updateStockTransaction($transactionId, array $data): bool
    {
        // Ambil transaksi lama
        $transaction = $this->stockTransactionRepository->getStockTransactionById($transactionId);
        if (!$transaction) {
            Log::error("Transaksi dengan ID {$transactionId} tidak ditemukan.");
            return false;
        }

        // Ambil produk terkait
        $product = Product::find($data['product_id']);
        if (!$product) {
            Log::error("Produk dengan ID {$data['product_id']} tidak ditemukan.");
            return false;
        }

        // Kembalikan stok lama sebelum update
        if ($transaction->type == 'Masuk') {
            $product->stock -= $transaction->quantity;
        } else {
            $product->stock += $transaction->quantity;
        }

        // Update dengan data baru
        if ($data['type'] == 'Masuk') {
            $product->stock += $data['quantity'];
        } else {
            if ($product->stock < $data['quantity']) {
                Log::error("Stok tidak mencukupi setelah update untuk produk ID {$data['product_id']}");
                return false;
            }
            $product->stock -= $data['quantity'];
        }

        $product->save();

        return $this->stockTransactionRepository->updateStockTransaction($transactionId, $data);
    }

    public function deleteStockTransaction($transactionId): bool
    {
        // Ambil transaksi sebelum dihapus
        $transaction = $this->stockTransactionRepository->getStockTransactionById($transactionId);
        if (!$transaction) {
            Log::error("Transaksi dengan ID {$transactionId} tidak ditemukan.");
            return false;
        }

        // Ambil produk terkait
        $product = Product::find($transaction->product_id);
        if (!$product) {
            Log::error("Produk dengan ID {$transaction->product_id} tidak ditemukan.");
            return false;
        }

        // Kembalikan stok sebelum transaksi dihapus
        if ($transaction->type == 'Masuk') {
            $product->stock -= $transaction->quantity;
        } else {
            $product->stock += $transaction->quantity;
        }
        $product->save();

        return $this->stockTransactionRepository->deleteStockTransaction($transactionId);
    }
}
