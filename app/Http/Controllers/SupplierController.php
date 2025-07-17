<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

  public function index(Request $request)
{
    $filters = $request->only('search');

    $suppliers = $this->supplierService->getAllSuppliers($filters);

    // Log aktivitas
    $this->supplierService->logActivity('Melihat daftar supplier');

    return Inertia::render('Suppliers/Index', [
        'suppliers' => $suppliers,
        'filters' => $filters, // <-- tambah ini
    ]);
}

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
        ]);

        $supplier = $this->supplierService->createSupplier($request->all());

        // Log aktivitas
        $this->supplierService->logActivity("Menambahkan supplier: {$request->name}");

        return redirect()->back()->with(
            $supplier
                ? ['success' => 'Supplier berhasil ditambahkan!']
                : ['error' => 'Gagal menambahkan supplier.']
        );
    }

    public function show($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);

        $this->supplierService->logActivity("Melihat detail supplier: {$supplier->name}");

        return response()->json($supplier);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
        ]);

        $supplier = $this->supplierService->updateSupplier($id, $request->all());

        $this->supplierService->logActivity("Memperbarui supplier: {$request->name}");

        return redirect()->back()->with(
            $supplier
                ? ['success' => 'Supplier berhasil diperbarui!']
                : ['error' => 'Gagal memperbarui supplier.']
        );
    }

    public function destroy($id)
    {
        $deleted = $this->supplierService->deleteSupplier($id);

        $this->supplierService->logActivity("Menghapus supplier ID: $id");

        return redirect()->back()->with(
            $deleted
                ? ['success' => 'Supplier berhasil dihapus!']
                : ['error' => 'Gagal menghapus supplier.']
        );
    }
}
