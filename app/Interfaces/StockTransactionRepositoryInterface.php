<?php

namespace App\Interfaces;

interface StockTransactionRepositoryInterface
{
    public function getAllStockTransactions();
    public function getStockTransactionById($stockTransactionId);
    public function createStockTransaction(array $stockTransactionDetails);
    public function updateStockTransaction($stockTransactionId, array $newDetails);
    public function deleteStockTransaction($stockTransactionId);
}
