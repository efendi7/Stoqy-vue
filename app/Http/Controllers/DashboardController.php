<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Models\Product;
use App\Models\StockTransaction;
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
        // Default periode: 30 hari terakhir jika tidak ada input
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get key metrics
        $totalProducts = Product::count();
        $lowStockItems = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        
        // Get transaction counts dengan filter periode
        $incomingTransactions = StockTransaction::whereBetween('transaction_date', 
            [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->where('type', 'Masuk')
            ->count();
            
        $outgoingTransactions = StockTransaction::whereBetween('transaction_date', 
            [$startDate.' 00:00:00', $endDate.' 23:59:59'])
            ->where('type', 'Keluar')
            ->count();
            
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(7))->count();
        
        $userRole = $this->userService->getUserRole(auth()->id());
        
        // Get data for top 10 products by stock
        $topProducts = Product::orderBy('stock', 'desc')
            ->limit(10)
            ->get();
            
        $stockLabels = $topProducts->pluck('name')->toArray();
        $stockData = $topProducts->pluck('stock')->toArray();
        
        // Prepare data for transaction chart - format untuk Chart.js
        $transactionLabels = [];
        $transactionData = [];
        
        // Generate date range dari start date ke end date
        $dateRange = Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate));
        
        foreach ($dateRange as $date) {
            $formattedDate = $date->format('Y-m-d');
            $displayDate = $date->format('d M');
            
            // Hitung jumlah transaksi untuk tanggal ini
            $count = StockTransaction::whereDate('transaction_date', $formattedDate)->count();
            
            $transactionLabels[] = $displayDate;
            $transactionData[] = $count;
        }
        
        // Get recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();
        
        // Debug log
        \Log::debug('Stock Chart Data:', [
            'labels' => $stockLabels,
            'data' => $stockData
        ]);
        
        \Log::debug('Transaction Chart Data:', [
            'labels' => $transactionLabels,
            'data' => $transactionData
        ]);
        
        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'incomingTransactions' => $incomingTransactions,
            'outgoingTransactions' => $outgoingTransactions,
            'activeUsers' => $activeUsers,
            'stockLabels' => $stockLabels,
            'stockData' => $stockData,
            'transactionLabels' => $transactionLabels,
            'transactionData' => $transactionData,
            'recentActivities' => $recentActivities,
            'userRole' => auth()->user()->role,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}