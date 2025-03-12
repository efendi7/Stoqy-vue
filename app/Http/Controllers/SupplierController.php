<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
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

        $this->supplierService->createSupplier($validated);

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);

        $this->supplierService->updateSupplier($supplier->id, $validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui!');
    }

    public function destroy(Supplier $supplier)
    {
        $this->supplierService->deleteSupplier($supplier->id);

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus!');
    }
}
