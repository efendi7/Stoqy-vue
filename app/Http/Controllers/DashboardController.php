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
        $user = auth()->user();
        $data = [];
    
        // Total user
        $totalUsers = User::count();
        // Total suppliers
        $totalSuppliers = Supplier::count();
    
        // Default periode: 30 hari terakhir jika tidak ada input
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->format('Y-m-d')));
    
        // Get key metrics
        $totalProducts = Product::count();
        $lowStockItems = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $availableStock = Product::whereColumn('stock', '>', 'minimum_stock')->count();
        $outOfStock = Product::where('stock', '=', 0)->count(); // Stok habis
        $activeUsers = User::where('is_logged_in', true)->count(); // Menghitung pengguna yang sedang login saat ini
    
        // Define admin metrics
        $adminMetrics = [
            ['label' => 'Total Users', 'value' => $totalUsers, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '👥'],
            ['label' => 'Total Suppliers', 'value' => $totalSuppliers, 'color' => 'bg-green-100 text-green-800', 'icon' => '🏭'],
            ['label' => 'Active Users', 'value' => $activeUsers, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⚡'],
        ];
    
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
    
        $viewData = [
            'availableStock' => $availableStock,
            'outOfStock' => $outOfStock,
            'totalSuppliers' => $totalSuppliers,
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
            'userRole' => $user->role,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ];
    
        // Warehouse Manager specific metrics and tasks
        if ($user->role === 'warehouse_manager') {
            // Today's transactions counts
            $todayIncomingTransactions = StockTransaction::where('type', 'Masuk')
                ->whereDate('transaction_date', Carbon::today())
                ->count();
    
            $todayOutgoingTransactions = StockTransaction::where('type', 'Keluar')
                ->whereDate('transaction_date', Carbon::today())
                ->count();
    
            // Pending transactions
            $pendingIncomingTasks = StockTransaction::where('type', 'Masuk')
                ->where('status', 'Pending')
                ->latest()
                ->limit(5)
                ->get();
    
            $pendingOutgoingTasks = StockTransaction::where('type', 'Keluar')
                ->where('status', 'Pending')
                ->latest()
                ->limit(5)
                ->get();
    
            // Add warehouse manager specific data to view
            $viewData['todayIncomingTransactions'] = $todayIncomingTransactions;
            $viewData['todayOutgoingTransactions'] = $todayOutgoingTransactions;
            $viewData['pendingIncomingTasks'] = $pendingIncomingTasks;
            $viewData['pendingOutgoingTasks'] = $pendingOutgoingTasks;
        }
    
        if ($user->role === 'admin') {
            $viewData['adminMetrics'] = $adminMetrics;
        }
    
        return view('dashboard', $viewData);
    }
    
}
