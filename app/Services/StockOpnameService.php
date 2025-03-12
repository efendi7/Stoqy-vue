<?php

namespace App\Services;

use App\Interfaces\StockOpnameRepositoryInterface;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class StockOpnameService
{
    protected $stockOpnameRepository;

    public function __construct(StockOpnameRepositoryInterface $stockOpnameRepository)
    {
        $this->stockOpnameRepository = $stockOpnameRepository;
    }

    public function storeStockOpname($data)
    {
        $stockOpname = $this->stockOpnameRepository->storeOrUpdateStockOpname($data);

        // Simpan log aktivitas
        ActivityLog::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'action' => "Melakukan stock opname untuk produk ID: {$data['product_id']}",
            'properties' => json_encode([
                'recorded_stock' => $data['recorded_stock'],
                'actual_stock' => $data['actual_stock'],
                'difference' => $data['difference'],
            ]),
        ]);

        return $stockOpname;
    }
}
