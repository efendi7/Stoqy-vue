<?php

namespace App\Interfaces;

use App\Models\StockTransaction;

interface StockTransactionRepositoryInterface
{
    public function getAllStockTransactions(): \Illuminate\Database\Eloquent\Collection;
    public function getStockTransactionById(int $stockTransactionId): ?StockTransaction;
    public function createStockTransaction(array $stockTransactionDetails): StockTransaction;
    public function updateStockTransaction(int $stockTransactionId, array $newDetails): bool;
    public function deleteStockTransaction(int $stockTransactionId): bool;
}
