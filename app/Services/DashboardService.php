<?php

namespace App\Services;

use App\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData(Request $request, $user)
    {
        Carbon::setLocale('id');
        
        // Periode waktu (default 30 hari terakhir)
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->format('Y-m-d')));
        
        // Get all user data
        $totalUsers = $this->dashboardRepository->getUserCount();
        $activeUsers = $this->dashboardRepository->getActiveUsersCount();
        
        // Get supplier data
        $totalSuppliers = $this->dashboardRepository->getSupplierCount();
        
        // Get product data
        $totalProducts = $this->dashboardRepository->getProductCount();
        $lowStockItems = $this->dashboardRepository->getLowStockCount();
        $availableStock = $this->dashboardRepository->getAvailableStockCount();
        $outOfStock = $this->dashboardRepository->getOutOfStockCount();
        $topProducts = $this->dashboardRepository->getTopProductsByStock(10);
        
        // Get transaction statistics
        $incomingTransactions = $this->dashboardRepository->getIncomingTransactionsCount();
        $outgoingTransactions = $this->dashboardRepository->getOutgoingTransactionsCount();
        $todayIncomingTransactions = $this->dashboardRepository->getTodayIncomingTransactionsCount();
        $todayOutgoingTransactions = $this->dashboardRepository->getTodayOutgoingTransactionsCount();
        
        // Get activity logs
        $recentActivities = $this->dashboardRepository->getTodayActivitiesPaginated(10);

        
        // Get transaction data per day in the selected period
        $dateRange = $startDate->daysUntil($endDate);
        $transactionLabels = [];
        $incomingTransactionData = [];
        $outgoingTransactionData = [];
        $combinedTransactionData = [];

        foreach ($dateRange as $date) {
            $formattedDate = $date->format('Y-m-d');
            $transactionLabels[] = $date->format('d M');

            $incomingCount = $this->dashboardRepository->getTransactionsCountByDate($formattedDate, 'Masuk', 'Diterima');
            $outgoingCount = $this->dashboardRepository->getTransactionsCountByDate($formattedDate, 'Keluar', 'Diterima');

            $incomingTransactionData[] = $incomingCount;
            $outgoingTransactionData[] = $outgoingCount;
            $combinedTransactionData[] = $incomingCount + $outgoingCount;
        }
        
        // Build view data
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
        
        // Add role-specific data
        $this->addRoleSpecificData($viewData, $user);
        
        return $viewData;
    }
    
    protected function addRoleSpecificData(&$viewData, $user)
    {
        // Default empty collections
        $viewData['pendingIncomingTasks'] = collect();
        $viewData['pendingOutgoingTasks'] = collect();
        $viewData['incomingTaskStaff'] = collect();
        $viewData['outgoingTaskStaff'] = collect();
        $viewData['completeTaskStaff'] = collect();
        
        //dd($viewData['completeTaskStaff']);


        // Warehouse manager role data
        if ($user->role === 'warehouse_manager') {
            $viewData['pendingIncomingTasks'] = $this->dashboardRepository->getPendingIncomingTransactions(5);
            $viewData['pendingOutgoingTasks'] = $this->dashboardRepository->getPendingOutgoingTransactions(5);
        }
        
        // Warehouse staff role data
        if ($user->role === 'warehouse_staff') {
            $viewData['incomingTaskStaff'] = $this->dashboardRepository->getIncomingTasksPaginated(10);
            $viewData['outgoingTaskStaff'] = $this->dashboardRepository->getOutgoingTasksPaginated(10);
            $viewData['completeTaskStaff'] = $this->dashboardRepository->getCompletedTasksPaginated(10);
        }
        
        // Admin role data
        if ($user->role === 'admin') {
            $viewData['adminMetrics'] = [
                ['label' => 'Total Users', 'value' => $viewData['totalUsers'], 'color' => 'bg-blue-100 text-blue-800', 'icon' => '👥'],
                ['label' => 'Total Suppliers', 'value' => $viewData['totalSuppliers'], 'color' => 'bg-green-100 text-green-800', 'icon' => '🏭'],
                ['label' => 'Active Users', 'value' => $viewData['activeUsers'], 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⚡']
            ];
        }
    }
}