<?php

namespace App\Services;

use App\Interfaces\ProductImportExportRepositoryInterface;

class ProductImportExportService
{
    protected $repository;

    public function __construct(ProductImportExportRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function exportProducts()
    {
        return $this->repository->exportProducts();
    }

    public function exportTemplate()
    {
        return $this->repository->exportTemplate();
    }

    public function importProducts($file)
    {
        return $this->repository->importProducts($file);
    }
}
