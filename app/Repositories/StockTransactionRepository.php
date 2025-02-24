<?php

namespace App\Repositories;

use App\Models\StockTransaction;
use App\Interfaces\StockTransactionRepositoryInterface;

class StockTransactionRepository implements StockTransactionRepositoryInterface
{
    public function getAllStockTransactions()
    {
        return StockTransaction::all();
    }

    public function getStockTransactionById($stockTransactionId)
    {
        return StockTransaction::findOrFail($stockTransactionId);
    }

    public function createStockTransaction(array $stockTransactionDetails)
    {
        return StockTransaction::create($stockTransactionDetails);
    }

    public function updateStockTransaction($stockTransactionId, array $newDetails)
    {
        return StockTransaction::whereId($stockTransactionId)->update($newDetails);
    }

    public function deleteStockTransaction($stockTransactionId)
    {
        return StockTransaction::destroy($stockTransactionId);
    }
}
