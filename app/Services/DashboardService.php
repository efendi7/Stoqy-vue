<?php

namespace App\Services;

use App\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData(Request $request, $user): array
    {
        Carbon::setLocale('id');

        // Data umum yang selalu ada untuk view
        $viewData = [
            'userRoleLabel' => $this->getUserRoleLabel($user->role),
            'userName' => $user->name,
            'user' => $user,
        ];

        $dashboardView = 'dashboard.default';

        switch ($user->role) {
            case 'admin':
                $dashboardView = 'dashboard.admin';
                $viewData = array_merge($viewData, $this->getAdminDashboardData($request));
                break;
            case 'warehouse_manager':
                $dashboardView = 'dashboard.warehouse_manager';
                $viewData = array_merge($viewData, $this->getWarehouseManagerDashboardData());
                break;
            case 'warehouse_staff':
                $dashboardView = 'dashboard.warehouse_staff';
                $viewData = array_merge($viewData, $this->getWarehouseStaffDashboardData($request));
                break;
        }

        $viewData['dashboardView'] = $dashboardView;

        return $viewData;
    }

    protected function getUserRoleLabel(string $role): string
    {
        return match ($role) {
            'admin' => 'Admin',
            'warehouse_manager' => 'Manajer Gudang',
            'warehouse_staff' => 'Staf Gudang',
            default => 'Pengguna',
        };
    }

    protected function getAdminDashboardData(Request $request): array
    {
        $startDate = Carbon::parse($request->input('start_date', now()->subDays(29)->toDateString()));
        $endDate = Carbon::parse($request->input('end_date', now()->toDateString()));
        $stockCategory = $request->input('stock_category', 'all');
        $stockSort = $request->input('stock_sort', 'desc');

        // --- OPTIMASI CHART DATA ---
        $transactionDataGrouped = $this->dashboardRepository->getTransactionCountsGroupedByDate($startDate, $endDate)->keyBy('date');

        $period = CarbonPeriod::create($startDate, $endDate);
        $transactionLabels = [];
        $incomingTransactionData = [];
        $outgoingTransactionData = [];
        $combinedTransactionData = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $transactionLabels[] = $date->format('d M');

            $incomingCount = $transactionDataGrouped[$formattedDate]->incoming_count ?? 0;
            $outgoingCount = $transactionDataGrouped[$formattedDate]->outgoing_count ?? 0;

            $incomingTransactionData[] = $incomingCount;
            $outgoingTransactionData[] = $outgoingCount;
            $combinedTransactionData[] = $incomingCount + $outgoingCount;
        }
        // --- AKHIR OPTIMASI ---

        $productsForChart = $this->dashboardRepository->getProductsForChart($stockCategory, $stockSort, 10);
        $allCategories = $this->dashboardRepository->getAllCategories();
        $stockLabels = [];
        $stockData = [];
        if ($productsForChart->isNotEmpty()) {
            foreach ($productsForChart as $product) {
                // Validasi tambahan untuk memastikan data konsisten
                if (!empty(trim($product->name)) && is_numeric($product->stock)) {
                    $stockLabels[] = trim($product->name);
                    $stockData[] = (int) $product->stock;
                }
            }
        }

        $totalProducts = $this->dashboardRepository->getProductCount();
        $availableStock = $this->dashboardRepository->getAvailableStockCount();
        $lowStockItems = $this->dashboardRepository->getLowStockCount();
        $outOfStock = $this->dashboardRepository->getOutOfStockCount();
        $incomingTransactions = array_sum($incomingTransactionData);
        $outgoingTransactions = array_sum($outgoingTransactionData);
        $totalUsers = $this->dashboardRepository->getUserCount();
        $totalSuppliers = $this->dashboardRepository->getSupplierCount();

        $metrics = [
            [
                'label' => 'Total Produk',
                'value' => $totalProducts,
                'icon' => '📦',
                'gradient' => 'from-green-100 to-emerald-100',
                'dark_gradient' => 'dark:from-green-500/20 dark:to-emerald-500/20',
                'border' => 'border-green-200',
                'dark_border' => 'dark:border-green-400/30',
                'text' => 'text-green-700',
                'dark_text' => 'dark:text-green-300',
                'delay' => '0',
            ],
            [
                'label' => 'Stok Tersedia',
                'value' => $availableStock,
                'icon' => '✅',
                'gradient' => 'from-blue-100 to-cyan-100',
                'dark_gradient' => 'dark:from-blue-500/20 dark:to-cyan-500/20',
                'border' => 'border-blue-200',
                'dark_border' => 'dark:border-blue-400/30',
                'text' => 'text-blue-700',
                'dark_text' => 'dark:text-blue-300',
                'delay' => '100',
            ],
            [
                'label' => 'Stok Rendah',
                'value' => $lowStockItems,
                'icon' => '⚠️',
                'gradient' => 'from-yellow-100 to-orange-100',
                'dark_gradient' => 'dark:from-yellow-500/20 dark:to-orange-500/20',
                'border' => 'border-yellow-200',
                'dark_border' => 'dark:border-yellow-400/30',
                'text' => 'text-yellow-700',
                'dark_text' => 'dark:text-yellow-300',
                'delay' => '200',
            ],
            [
                'label' => 'Stok Habis',
                'value' => $outOfStock,
                'icon' => '❌',
                'gradient' => 'from-red-100 to-pink-100',
                'dark_gradient' => 'dark:from-red-500/20 dark:to-pink-500/20',
                'border' => 'border-red-200',
                'dark_border' => 'dark:border-red-400/30',
                'text' => 'text-red-700',
                'dark_text' => 'dark:text-red-300',
                'delay' => '300',
            ],
            [
                'label' => 'Transaksi Masuk',
                'value' => $incomingTransactions,
                'icon' => '📥',
                'gradient' => 'from-indigo-100 to-purple-100',
                'dark_gradient' => 'dark:from-indigo-500/20 dark:to-purple-500/20',
                'border' => 'border-indigo-200',
                'dark_border' => 'dark:border-indigo-400/30',
                'text' => 'text-indigo-700',
                'dark_text' => 'dark:text-indigo-300',
                'delay' => '400',
            ],
            [
                'label' => 'Transaksi Keluar',
                'value' => $outgoingTransactions,
                'icon' => '📤',
                'gradient' => 'from-purple-100 to-pink-100',
                'dark_gradient' => 'dark:from-purple-500/20 dark:to-pink-500/20',
                'border' => 'border-purple-200',
                'dark_border' => 'dark:border-purple-400/30',
                'text' => 'text-purple-700',
                'dark_text' => 'dark:text-purple-300',
                'delay' => '500',
            ],
            [
                'label' => 'Total Users',
                'value' => $totalUsers,
                'icon' => '👥',
                'gradient' => 'from-teal-100 to-cyan-100',
                'dark_gradient' => 'dark:from-teal-500/20 dark:to-cyan-500/20',
                'border' => 'border-teal-200',
                'dark_border' => 'dark:border-teal-400/30',
                'text' => 'text-teal-700',
                'dark_text' => 'dark:text-teal-300',
                'delay' => '600',
            ],
            [
                'label' => 'Total Suppliers',
                'value' => $totalSuppliers,
                'icon' => '🏭',
                'gradient' => 'from-emerald-100 to-green-100',
                'dark_gradient' => 'dark:from-emerald-500/20 dark:to-green-500/20',
                'border' => 'border-emerald-200',
                'dark_border' => 'dark:border-emerald-400/30',
                'text' => 'text-emerald-700',
                'dark_text' => 'dark:text-emerald-300',
                'delay' => '700',
            ],
        ];

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalUsers' => $this->dashboardRepository->getUserCount(),
            'activeUsers' => $this->dashboardRepository->getActiveUsersCount(),
            'totalSuppliers' => $this->dashboardRepository->getSupplierCount(),
            'totalProducts' => $this->dashboardRepository->getProductCount(),
            'lowStockItems' => $this->dashboardRepository->getLowStockCount(),
            'availableStock' => $this->dashboardRepository->getAvailableStockCount(),
            'outOfStock' => $this->dashboardRepository->getOutOfStockCount(),
            'incomingTransactions' => array_sum($incomingTransactionData), // Lebih akurat dari hasil kalkulasi
            'outgoingTransactions' => array_sum($outgoingTransactionData), // Lebih akurat dari hasil kalkulasi
            'recentActivities' => $this->dashboardRepository->getTodayActivitiesPaginated(10),
            'stockLabels' => $stockLabels,
            'stockData' => $stockData,
            'transactionLabels' => $transactionLabels,
            'incomingTransactionData' => $incomingTransactionData,
            'outgoingTransactionData' => $outgoingTransactionData,
            'combinedTransactionData' => $combinedTransactionData,
            'allCategories' => $allCategories,
            'selectedCategory' => $stockCategory,
            'selectedStockSort' => $stockSort,
            'metrics' => $metrics,
        ];
    }

    protected function getWarehouseManagerDashboardData(): array
    {
        return [
            'todayIncomingTransactions' => $this->dashboardRepository->getTodayIncomingTransactionsCount(),
            'todayOutgoingTransactions' => $this->dashboardRepository->getTodayOutgoingTransactionsCount(),
            'pendingIncomingTasks' => $this->dashboardRepository->getManagerPendingIncomingTransactions(5),
            'pendingOutgoingTasks' => $this->dashboardRepository->getManagerPendingOutgoingTransactions(5),
            'totalProducts' => $this->dashboardRepository->getProductCount(),
            'lowStockItems' => $this->dashboardRepository->getLowStockCount(),
            'availableStock' => $this->dashboardRepository->getAvailableStockCount(),
            'outOfStock' => $this->dashboardRepository->getOutOfStockCount(),
        ];
    }

    protected function getWarehouseStaffDashboardData(Request $request): array
    {
        $incomingTaskStaff = $this->dashboardRepository->getIncomingTasksPaginated(5, $request->input('incoming_page', 1), 'incoming_page');
        $outgoingTaskStaff = $this->dashboardRepository->getOutgoingTasksPaginated(5, $request->input('outgoing_page', 1), 'outgoing_page');
        $completeTaskStaff = $this->dashboardRepository->getCompletedTasksPaginated(5, $request->input('complete_page', 1), 'complete_page');

        return [
            'incomingTaskStaff' => $incomingTaskStaff,
            'outgoingTaskStaff' => $outgoingTaskStaff,
            'completeTaskStaff' => $completeTaskStaff,
            'pendingIncomingTasksCount' => $incomingTaskStaff->total(),
            'pendingOutgoingTasksCount' => $outgoingTaskStaff->total(),
        ];
    }

    public function debugChartData($productsForChart, $stockLabels, $stockData)
    {
        \Log::info('=== CHART DATA DEBUG ===');
        \Log::info('Products count: ' . $productsForChart->count());

        foreach ($productsForChart as $index => $product) {
            \Log::info("Index {$index}: {$product->name} = {$product->stock}");
        }

        \Log::info('Stock Labels: ' . json_encode($stockLabels));
        \Log::info('Stock Data: ' . json_encode($stockData));
        \Log::info('=== END DEBUG ===');
    }
}
