<?php
namespace App\Services;

use App\Models\Supplier;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class SupplierService
{
    public function getAllSuppliers()
    {
        return Supplier::paginate(10);
    }

    public function getSupplierById($id)
    {
        return Supplier::findOrFail($id);
    }

    public function createSupplier(array $data)
    {
        try {
            // Validasi
            $validated = $this->validateSupplier($data);

            $supplier = Supplier::create($validated);

            // Log aktivitas
            $this->logActivity("Menambahkan supplier: {$supplier->name}");

            return $supplier;
        } catch (Exception $e) {
            Log::error("Gagal menambahkan supplier: " . $e->getMessage());
            return null;
        }
    }

    public function updateSupplier($id, array $data)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $validated = $this->validateSupplier($data);

            $supplier->update($validated);

            // Log aktivitas
            $this->logActivity("Memperbarui supplier: {$supplier->name}");

            return $supplier;
        } catch (ModelNotFoundException $e) {
            Log::error("Supplier dengan ID {$id} tidak ditemukan!");
            return null;
        } catch (Exception $e) {
            Log::error("Gagal memperbarui supplier: " . $e->getMessage());
            return null;
        }
    }

    public function deleteSupplier($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();

            // Log aktivitas
            $this->logActivity("Menghapus supplier: {$supplier->name}");

            return true;
        } catch (ModelNotFoundException $e) {
            Log::error("Supplier dengan ID {$id} tidak ditemukan!");
            return false;
        } catch (Exception $e) {
            Log::error("Gagal menghapus supplier: " . $e->getMessage());
            return false;
        }
    }

    private function validateSupplier($data)
    {
        return validator($data, [
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
        ])->validate();
    }

    public function logActivity($action, $properties = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role,
            'action' => $action,
            'properties' => $properties ? json_encode($properties) : null,
        ]);
    }
}
