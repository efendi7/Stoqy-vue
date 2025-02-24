<?php

namespace App\Services;

use App\Interfaces\SupplierRepositoryInterface;
use Illuminate\Support\Collection;

class SupplierService
{
    protected $supplierRepository;

    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }


    public function getAllSuppliers(): Collection
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
