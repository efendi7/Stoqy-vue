<?php

namespace App\Interfaces;
use Illuminate\Pagination\LengthAwarePaginator;

interface SupplierRepositoryInterface
{
    public function getAllSuppliers(): LengthAwarePaginator;
    public function getSupplierById($supplierId);
    public function createSupplier(array $supplierDetails);
    public function updateSupplier($supplierId, array $newDetails);
    public function deleteSupplier($supplierId);
}
