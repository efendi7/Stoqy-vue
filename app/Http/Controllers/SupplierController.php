<?php
namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();

        // Log aktivitas
        $this->supplierService->logActivity('Melihat daftar supplier');

        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        // Log aktivitas
        $this->supplierService->logActivity('Mengakses formulir tambah supplier');

        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $supplier = $this->supplierService->createSupplier($request->all());

        return $supplier
            ? redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan!')
            : redirect()->back()->with('error', 'Gagal menambahkan supplier.');
    }

    public function edit($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);

        // Log aktivitas
        $this->supplierService->logActivity("Mengakses formulir edit supplier: {$supplier->name}");

        return view('suppliers.edit', compact('supplier'));
    }

    public function show($id)
    {
        $supplier = $this->supplierService->getSupplierById($id);

        // Log aktivitas
        $this->supplierService->logActivity("Melihat detail supplier: {$supplier->name}");

        return view('suppliers.show', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = $this->supplierService->updateSupplier($id, $request->all());

        return $supplier
            ? redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui!')
            : redirect()->back()->with('error', 'Gagal memperbarui supplier.');
    }

    public function destroy($id)
    {
        $deleted = $this->supplierService->deleteSupplier($id);

        return $deleted
            ? redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!')
            : redirect()->back()->with('error', 'Gagal menghapus supplier.');
    }
}
