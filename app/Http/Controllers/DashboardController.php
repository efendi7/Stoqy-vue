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
        Carbon::setLocale('id'); // Mengatur locale Carbon ke bahasa Indonesia
        $user = auth()->user();
        
        // Total user & supplier
        $totalUsers = User::count();
        $totalSuppliers = Supplier::count();
        
        // Periode waktu (default 30 hari terakhir)
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->format('Y-m-d')));
        
        // Statistik produk
        $totalProducts = Product::count();
        $lowStockItems = Product::whereColumn('stock', '<', 'minimum_stock')->count();
        $availableStock = Product::whereColumn('stock', '>=', 'minimum_stock')->count();
        $outOfStock = Product::where('stock', '=', 0)->count();
        
        // Statistik pengguna
        $activeUsers = User::where('is_logged_in', true)->count();
        
        // Statistik transaksi masuk dan keluar yang diterima
        $incomingTransactions = StockTransaction::where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->count();

        $outgoingTransactions = StockTransaction::where('type', 'Keluar')
            ->where('status', 'Diterima')
            ->count();
        
        // Statistik transaksi per hari dalam periode
        $dateRange = $startDate->daysUntil($endDate);
        $transactionLabels = [];
        $incomingTransactionData = [];
        $outgoingTransactionData = [];
        $combinedTransactionData = [];

        foreach ($dateRange as $date) {
            $formattedDate = $date->format('Y-m-d');
            $transactionLabels[] = $date->format('d M');

            // Hitung transaksi masuk yang diterima pada tanggal tertentu
            $incomingCount = StockTransaction::whereDate('transaction_date', $formattedDate)
                ->where('type', 'Masuk')
                ->where('status', 'Diterima')
                ->count();

            // Hitung transaksi keluar yang diterima pada tanggal tertentu
            $outgoingCount = StockTransaction::whereDate('transaction_date', $formattedDate)
                ->where('type', 'Keluar')
                ->where('status', 'Diterima')
                ->count();

            $incomingTransactionData[] = $incomingCount;
            $outgoingTransactionData[] = $outgoingCount;

            // Hitung transaksi gabungan (Masuk + Keluar)
            $combinedTransactionData[] = $incomingCount + $outgoingCount;
        }

        // Statistik transaksi hari ini yang diterima
        $today = Carbon::now()->format('Y-m-d');
        $todayIncomingTransactions = StockTransaction::whereDate('transaction_date', $today)
            ->where('type', 'Masuk')
            ->where('status', 'Diterima')
            ->count();

        $todayOutgoingTransactions = StockTransaction::whereDate('transaction_date', $today)
            ->where('type', 'Keluar')
            ->where('status', 'Diterima')
            ->count();

        // Produk dengan stok tertinggi
        $topProducts = Product::orderByDesc('stock')->limit(10)->get(['name', 'stock']);
        
       // Aktivitas terbaru hanya untuk hari ini
$recentActivities = ActivityLog::with('user')
->whereDate('created_at', Carbon::today()) // Hanya aktivitas hari ini
->latest()
->limit(10)
->paginate(10);

    
        // Data untuk tampilan
        $viewData = [
            'totalUsers' => $totalUsers,
            'totalSuppliers' => $totalSuppliers,
            'totalProducts' => $totalProducts,
            'lowStockItems' => $lowStockItems,
            'availableStock' => $availableStock,
            'outOfStock' => $outOfStock,
            'activeUsers' => $activeUsers,
            'incomingTransactions' => $incomingTransactions,
            'outgoingTransactions' => $outgoingTransactions,
            'transactionLabels' => $transactionLabels,
            'incomingTransactionData' => $incomingTransactionData,
            'outgoingTransactionData' => $outgoingTransactionData,
            'combinedTransactionData' => $combinedTransactionData,
            'todayIncomingTransactions' => $todayIncomingTransactions,
            'todayOutgoingTransactions' => $todayOutgoingTransactions,
            'stockLabels' => $topProducts->pluck('name')->toArray(),
            'stockData' => $topProducts->pluck('stock')->toArray(),
            'recentActivities' => $recentActivities,
            'userRole' => $user->role,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ];
    
        // Data tambahan berdasarkan role pengguna
        if ($user->role === 'warehouse_staff') {
            // Tugas barang masuk hanya untuk warehouse_staff
            $incomingTaskStaff = StockTransaction::where('type', 'Masuk')
                ->where('status', 'Pending')
                ->latest()
                ->limit(5)
                ->get();

            $outgoingTaskStaff = StockTransaction::where('type', 'Keluar')
                ->where('status', 'Pending')
                ->latest()
                ->limit(5)
                ->get();

                $completeTaskStaff = StockTransaction::whereIn('status', ['Diterima', 'Ditolak', 'Confirmed'])
                ->latest()
                ->limit(5)
                ->get();

            $viewData['incomingTaskStaff'] = $incomingTaskStaff;
            $viewData['outgoingTaskStaff'] = $outgoingTaskStaff;
            $viewData['completeTaskStaff'] = $completeTaskStaff;
        } else {
            $viewData['incomingTaskStaff'] = collect(); // Koleksi kosong untuk non-warehouse_staff
            $viewData['outgoingTaskStaff'] = collect(); // Koleksi kosong
            $viewData['completeTaskStaff'] = collect(); // Koleksi kosong
        }

        if ($user->role === 'warehouse_manager') {
            $viewData['pendingOutgoingTasks'] = StockTransaction::where('type', 'Keluar')
                ->where('status', 'Pending')
                ->latest()
                ->limit(5)
                ->get();
        } else {
            $viewData['pendingOutgoingTasks'] = collect();
        }

        // Data tambahan untuk admin
        if ($user->role === 'admin') {
            $viewData['adminMetrics'] = [
                ['label' => 'Total Users', 'value' => $totalUsers, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '👥'],
                ['label' => 'Total Suppliers', 'value' => $totalSuppliers, 'color' => 'bg-green-100 text-green-800', 'icon' => '🏭'],
                ['label' => 'Active Users', 'value' => $activeUsers, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⚡']
            ];
        }

        return view('dashboard', $viewData); // Hanya satu return view
    }
}
