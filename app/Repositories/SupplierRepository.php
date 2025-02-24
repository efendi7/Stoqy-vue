<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Interfaces\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function getAllSuppliers()
    {
        return Supplier::all();
    }

    public function getSupplierById($supplierId)
    {
        return Supplier::findOrFail($supplierId);
    }

    public function createSupplier(array $supplierDetails)
    {
        return Supplier::create($supplierDetails);
    }

    public function updateSupplier($supplierId, array $newDetails)
    {
        return Supplier::whereId($supplierId)->update($newDetails);
    }

    public function deleteSupplier($supplierId)
    {
        return Supplier::destroy($supplierId);
    }
}
