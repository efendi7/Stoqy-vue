<?php

namespace App\Interfaces;

interface SupplierRepositoryInterface
{
    public function getAllSuppliers();
    public function getSupplierById($supplierId);
    public function createSupplier(array $supplierDetails);
    public function updateSupplier($supplierId, array $newDetails);
    public function deleteSupplier($supplierId);
}
