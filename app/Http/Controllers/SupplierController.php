<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

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
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => 'required|email|max:255', 
        ]);

        $supplier = $this->supplierService->createSupplier($validated);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menambahkan supplier: {$supplier->name}",
            'properties' => json_encode([
                'supplier_id' => $supplier->id,
                'data' => $validated,
            ]),
        ]);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        // Simpan data lama sebelum update
        $oldData = $supplier->toArray();
        $data = $request->except(['_token', '_method']);

        $this->supplierService->updateSupplier($supplier->id, $data);

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

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        // Simpan data supplier sebelum dihapus
        $supplierData = $supplier->toArray();

        $this->supplierService->deleteSupplier($supplier->id);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => auth()->id(),
            'role' => auth()->user()->role, 
            'action' => "Menghapus supplier: {$supplier->name}",
            'properties' => json_encode($supplierData),
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
