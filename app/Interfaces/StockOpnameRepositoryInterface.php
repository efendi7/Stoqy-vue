<?php

namespace App\Interfaces;

interface StockOpnameRepositoryInterface
{
    public function getAllStockOpnames();
    public function findStockOpnameByProductId($productId);
    public function storeOrUpdateStockOpname($data);
}
