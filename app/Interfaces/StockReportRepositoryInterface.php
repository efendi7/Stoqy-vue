<?php
namespace App\Interfaces;

interface StockReportRepositoryInterface
{
    public function getFilteredStock(array $filters);
    public function getCategories();
}
