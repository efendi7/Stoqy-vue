<?php
namespace App\Services;

use App\Interfaces\StockReportRepositoryInterface;

class StockReportService
{
    protected $stockReportRepository;

    public function __construct(StockReportRepositoryInterface $stockReportRepository)
    {
        $this->stockReportRepository = $stockReportRepository;
    }

    public function getStockReport(array $filters)
    {
        return $this->stockReportRepository->getFilteredStock($filters);
    }

    public function getCategories()
    {
        return $this->stockReportRepository->getCategories();
    }
}
