<?php

namespace App\Services;

use App\Interfaces\SupplierRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierService
{
    protected $supplierRepository;

    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }


    public function getAllSuppliers(): LengthAwarePaginator
    {
        return $this->supplierRepository->getAllSuppliers();
    }

    public function createSupplier(array $data)
    {
        return $this->supplierRepository->createSupplier($data);
    }

    public function updateSupplier($supplierId, array $data): bool
    {
        return $this->supplierRepository->updateSupplier($supplierId, $data);
    }

    public function deleteSupplier($supplierId): bool
    {
        return $this->supplierRepository->deleteSupplier($supplierId);
    }
}