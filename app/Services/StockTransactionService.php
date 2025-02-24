<?php

namespace App\Services;

use App\Interfaces\StockTransactionRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class StockTransactionService
{
    protected $stockTransactionRepository;

    public function __construct(StockTransactionRepositoryInterface $stockTransactionRepository)
    {
        $this->stockTransactionRepository = $stockTransactionRepository;
    }

    public function getAllTransactions()
    {
        return $this->stockTransactionRepository->getAllStockTransactions();
    }


    public function createTransaction(array $data)
    {
        $transaction = $this->stockTransactionRepository->createTransaction($data);

        // Update product stock
        $product = Product::find($data['product_id']);
        if ($data['type'] == 'Masuk') {
            $product->stock += $data['quantity'];
        } else {
            $product->stock -= $data['quantity'];
        }
        $product->save();

        Log::info('Transaksi stok berhasil dicatat: ', $transaction->toArray());
        
        return $transaction;
    }

    public function updateTransaction($transactionId, array $data): bool
    {
        return $this->stockTransactionRepository->updateTransaction($transactionId, $data);
    }

    public function deleteTransaction($transactionId): bool
    {
        return $this->stockTransactionRepository->deleteTransaction($transactionId);
    }
}
