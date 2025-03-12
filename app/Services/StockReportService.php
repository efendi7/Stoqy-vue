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

    public function getStockReport($filters)
    {
        return $this->stockReportRepository->getStockReport($filters);
    }
}
