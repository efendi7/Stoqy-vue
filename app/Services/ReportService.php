<?php

namespace App\Services;

use App\Repositories\ReportRepository;

class ReportService
{
    protected $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getStockReport($startDate, $endDate, $categoryId = null)
    {
        return $this->reportRepository->getStockReport($startDate, $endDate, $categoryId);
    }

    public function getIncomingTransactions($startDate, $endDate)
    {
        return $this->reportRepository->getIncomingTransactions($startDate, $endDate);
    }

    public function getOutgoingTransactions($startDate, $endDate)
    {
        return $this->reportRepository->getOutgoingTransactions($startDate, $endDate);
    }

    public function getCategories()
    {
        return $this->reportRepository->getCategories();
    }
}
