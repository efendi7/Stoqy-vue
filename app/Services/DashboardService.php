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
        
        // Ambil semua nilai filter dari request
        $stockCategory = $request->input('stock_category', 'all');
        $stockSort = $request->input('stock_sort', 'desc');
        $stockLimit = (int) $request->input('stock_limit', 10);
        
        // FIX 1: Ambil nilai filter status transaksi dari request
        $transactionStatus = $request->input('transaction_status', 'all');

        // --- Data Chart Transaksi ---
        // FIX 2: Teruskan filter status transaksi ke repository
        $transactionDataGrouped = $this->dashboardRepository->getTransactionCountsGroupedByDate($startDate, $endDate, $transactionStatus)->keyBy('date');
        
        $period = CarbonPeriod::create($startDate, $endDate);
        $transactionLabels = [];
        $incomingTransactionData = [];
        $outgoingTransactionData = [];

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $transactionLabels[] = $date->format('d M');
            $incomingTransactionData[] = $transactionDataGrouped[$formattedDate]->incoming_count ?? 0;
            $outgoingTransactionData[] = $transactionDataGrouped[$formattedDate]->outgoing_count ?? 0;
        }

        // --- Data Chart Stok ---
        $productsForChart = $this->dashboardRepository->getProductsForChart($stockCategory, $stockSort, $stockLimit);
        
        $allCategories = $this->dashboardRepository->getAllCategories();
        $stockLabels = $productsForChart->pluck('name')->toArray();
        $stockData = $productsForChart->pluck('stock')->toArray();

        // --- Data Metrik ---
        $metrics = [
            [
                'label' => 'Total Produk',
                'value' => $this->dashboardRepository->getProductCount(),
                'icon' => '📦',
                'delay' => 0,
            ],
            [
                'label' => 'Stok Tersedia',
                'value' => $this->dashboardRepository->getAvailableStockCount(),
                'icon' => '✅',
                'delay' => 100,
            ],
            [
                'label' => 'Stok Rendah',
                'value' => $this->dashboardRepository->getLowStockCount(),
                'icon' => '⚠️',
                'delay' => 200,
            ],
            [
                'label' => 'Stok Habis',
                'value' => $this->dashboardRepository->getOutOfStockCount(),
                'icon' => '❌',
                'delay' => 300,
            ],
        ];

        return [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'metrics' => $metrics,
            'incomingTransactions' => array_sum($incomingTransactionData),
            'outgoingTransactions' => array_sum($outgoingTransactionData),
            'stockLabels' => $stockLabels,
            'stockData' => $stockData,
            'transactionLabels' => $transactionLabels,
            'incomingTransactionData' => $incomingTransactionData,
            'outgoingTransactionData' => $outgoingTransactionData,
            'allCategories' => $allCategories,
            
            // Kirim kembali nilai filter yang sedang aktif ke Vue
            'selectedCategory' => $stockCategory,
            'selectedStockSort' => $stockSort,
            'selectedStockLimit' => $stockLimit,
            // FIX 3: Kirim kembali nilai filter status transaksi yang aktif
            'selectedTransactionStatus' => $transactionStatus,

            'recentActivities' => $this->dashboardRepository->getTodayActivitiesPaginated(10),
        ];
    }

   protected function getWarehouseManagerDashboardData(): array
{
    // 1. Susun semua data metrik ke dalam array terstruktur
    $metrics = [
        [
            'label' => 'Total Produk',
            'value' => $this->dashboardRepository->getProductCount(),
            'icon' => '📦'
        ],
        [
            'label' => 'Stok Tersedia',
            'value' => $this->dashboardRepository->getAvailableStockCount(),
            'icon' => '✅'
        ],
        [
            'label' => 'Stok Rendah',
            'value' => $this->dashboardRepository->getLowStockCount(),
            'icon' => '⚠️'
        ],
        [
            'label' => 'Stok Habis',
            'value' => $this->dashboardRepository->getOutOfStockCount(),
            'icon' => '❌'
        ],
        [
            'label' => 'Transaksi Masuk Hari Ini',
            'value' => $this->dashboardRepository->getTodayIncomingTransactionsCount(),
            'icon' => '📥'
        ],
        [
            'label' => 'Transaksi Keluar Hari Ini',
            'value' => $this->dashboardRepository->getTodayOutgoingTransactionsCount(),
            'icon' => '📤'
        ],
    ];

    // 2. Kembalikan array utama dengan kunci 'metrics' dan data lainnya
    return [
        'metrics' => $metrics, // Kirim data metrik dengan struktur yang benar
        'pendingIncoming' => $this->dashboardRepository->getManagerPendingIncomingTransactions(5), // Ubah nama key agar konsisten dengan Vue
        'pendingOutgoing' => $this->dashboardRepository->getManagerPendingOutgoingTransactions(5), // Ubah nama key agar konsisten dengan Vue
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
