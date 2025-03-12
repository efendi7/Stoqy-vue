<?php
namespace App\Interfaces;

interface StockReportRepositoryInterface
{
    public function getStockReport($filters);
}
