<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier; 
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller 
{
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function index(Request $request)
    {
        // Total user
        $totalUsers = User::count(); 

        // Default periode: 30 hari terakhir jika tidak ada input
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->format('Y-m-d')));
    
        // Get key metrics
        $totalProducts = Product::count();
        $lowStockItems = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $availableStock = Product::whereColumn('stock', '>', 'minimum_stock')->count();
        $outOfStock = Product::where('stock', '=', 0)->count(); // Stok habis
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(7))->count();
    
        // Dapatkan rentang tanggal
        $dateRange = $startDate->daysUntil($endDate);
    
        $transactionLabels = [];
        $incomingTransactionData = [];
        $outgoingTransactionData = [];
        $combinedTransactionData = [];
    
        foreach ($dateRange as $date) {
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M');
    
            // Hitung transaksi masuk dan keluar per hari
            $incomingCount = StockTransaction::whereDate('transaction_date', $formattedDate)
                ->where('type', 'Masuk')
                ->count();
                
            $outgoingCount = StockTransaction::whereDate('transaction_date', $formattedDate)
                ->where('type', 'Keluar')
                ->count();
    
            // Simpan data ke array
            $transactionLabels[] = $displayDate;
            $incomingTransactionData[] = $incomingCount;
            $outgoingTransactionData[] = $outgoingCount;
            $combinedTransactionData[] = $incomingCount + $outgoingCount;
        }
    
        // Get data for top 10 products by stock
        $topProducts = Product::orderByDesc('stock')->limit(10)->get(['name', 'stock']);
        $stockLabels = $topProducts->pluck('name')->toArray();
        $stockData = $topProducts->pluck('stock')->toArray();
    
        // Get recent activities
        $recentActivities = ActivityLog::with('user')->latest()->limit(10)->get();
    
        return view('dashboard', [
            'availableStock' => $availableStock,
            'outOfStock' => $outOfStock,
            'totalSuppliers' => Supplier::count(),
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'incomingTransactions' => array_sum($incomingTransactionData),
            'outgoingTransactions' => array_sum($outgoingTransactionData),
            'activeUsers' => $activeUsers,
            'stockLabels' => $stockLabels,
            'stockData' => $stockData,
            'transactionLabels' => $transactionLabels,
            'incomingTransactionData' => $incomingTransactionData,
            'outgoingTransactionData' => $outgoingTransactionData,
            'combinedTransactionData' => $combinedTransactionData,
            'recentActivities' => $recentActivities,
            'userRole' => auth()->user()->role,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ]);
    }
}
