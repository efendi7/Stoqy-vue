<?php
namespace App\Repositories;

use App\Interfaces\SupplierRepositoryInterface;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function getAllSuppliers(): LengthAwarePaginator
    {
        return Supplier::paginate(10);
    }

    // Tambahkan tipe return ?Supplier
    public function getSupplierById($supplierId): ?Supplier
    {
        return Supplier::find($supplierId);
    }

    public function createSupplier(array $supplierDetails): Supplier
    {
        return Supplier::create($supplierDetails);
    }

    public function updateSupplier($supplierId, array $newDetails): bool
    {
        $supplier = Supplier::findOrFail($supplierId);
        return $supplier->update($newDetails);
    }

    public function deleteSupplier($supplierId): bool
    {
        $supplier = Supplier::findOrFail($supplierId);
        return $supplier->delete();
    }
}
