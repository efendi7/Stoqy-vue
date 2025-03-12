<?php
namespace App\Interfaces;

use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

interface SupplierRepositoryInterface
{
    public function getAllSuppliers(): LengthAwarePaginator;
    public function getSupplierById($supplierId): ?Supplier;
    public function createSupplier(array $supplierDetails): Supplier;
    public function updateSupplier($supplierId, array $newDetails): bool;
    public function deleteSupplier($supplierId): bool;
}
