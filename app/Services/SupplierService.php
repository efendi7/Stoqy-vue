<?php
namespace App\Services;

use App\Interfaces\SupplierRepositoryInterface;
use App\Models\ActivityLog;
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
        $supplier = $this->supplierRepository->createSupplier($data);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menambahkan supplier: {$supplier->name}",
            'properties' => json_encode([
                'supplier_id' => $supplier->id,
                'data' => $data,
            ]),
        ]);

        return $supplier;
    }

    public function updateSupplier($supplierId, array $data): bool
    {
        $supplier = $this->supplierRepository->getSupplierById($supplierId);
        $oldData = $supplier->toArray();
        $updated = $this->supplierRepository->updateSupplier($supplierId, $data);

        if ($updated) {
            // Simpan log aktivitas
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role, 
                'action' => "Mengedit supplier: {$supplier->name}",
                'properties' => json_encode([
                    'before' => $oldData,
                    'after' => $data,
                ]),
            ]);
        }

        return $updated;
    }

    public function deleteSupplier($supplierId): bool
    {
        $supplier = $this->supplierRepository->getSupplierById($supplierId);
        $supplierData = $supplier->toArray();
        $deleted = $this->supplierRepository->deleteSupplier($supplierId);

        if ($deleted) {
            // Simpan log aktivitas
            ActivityLog::create([
                'user_id' => auth()->id(),
                'role' => auth()->user()->role, 
                'action' => "Menghapus supplier: {$supplier->name}",
                'properties' => json_encode($supplierData),
            ]);
        }

        return $deleted;
    }
}
