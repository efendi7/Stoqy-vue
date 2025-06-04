<?php

namespace App\Services;

use App\Interfaces\DashboardRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator; // Ensure this is imported

class DashboardService
{
    protected $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * Get dashboard data based on user role.
     *
     * @param Request $request The incoming HTTP request.
     * @param \App\Models\User $user The authenticated user object.
     * @return array An associative array containing dashboard data and the view path.
     */
    public function getDashboardData(Request $request, $user): array
    {
        Carbon::setLocale('id');

        $viewData = [];

        // Common data for all roles (e.g., for welcome message)
        // Assuming you have a getUserRoleLabel method somewhere or in your UserService
        $viewData['userRoleLabel'] = $this->getUserRoleLabel($user->role);
        $viewData['userName'] = $user->name;

        // Initialize dashboardView to a default in case of an unknown role
        $dashboardView = 'dashboard.default'; // Ensure you have a generic default dashboard view

        switch ($user->role) {
            case 'admin':
                $dashboardView = 'dashboard.admin'; // Path to your admin dashboard Blade file
                $viewData = array_merge($viewData, $this->getAdminDashboardData($request));
                break;
            case 'warehouse_manager':
                $dashboardView = 'dashboard.warehouse_manager'; // Path to your warehouse manager dashboard Blade file
                $viewData = array_merge($viewData, $this->getWarehouseManagerDashboardData());
                break;
            case 'warehouse_staff':
                $dashboardView = 'dashboard.warehouse_staff'; // Path to your warehouse staff dashboard Blade file
                $viewData = array_merge($viewData, $this->getWarehouseStaffDashboardData($request));
                break;
            // Add other roles as needed
        }

        // Always include the determined view path in the returned data
        $viewData['dashboardView'] = $dashboardView;

        return $viewData;
    }

    /**
     * Helper to get user role label.
     * You might move this to a dedicated UserService if it's used more broadly.
     */
    protected function getUserRoleLabel(string $role): string
    {
        return match ($role) {
            'admin' => 'Admin',
            'warehouse_manager' => 'Manajer Gudang',
            'warehouse_staff' => 'Staf Gudang',
            default => 'Pengguna',
        };
    }

    /**
     * Get data specific to the Admin dashboard.
     */
    protected function getAdminDashboardData(Request $request): array
    {
        $startDate = Carbon::parse($request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d')));
        $endDate = Carbon::parse($request->input('end_date', Carbon::now()->format('Y-m-d')));

        // Admin-specific counts/metrics
        $totalUsers = $this->dashboardRepository->getUserCount();
        $activeUsers = $this->dashboardRepository->getActiveUsersCount();
        $totalSuppliers = $this->dashboardRepository->getSupplierCount();

        // Common product metrics (used by Admin and Warehouse Manager)
        $totalProducts = $this->dashboardRepository->getProductCount();
        $lowStockItems = $this->dashboardRepository->getLowStockCount();
        $availableStock = $this->dashboardRepository->getAvailableStockCount();
        $outOfStock = $this->dashboardRepository->getOutOfStockCount();
        $topProducts = $this->dashboardRepository->getTopProductsByStock(10);

        // Convert topProducts to separate arrays for chart data
        $stockLabels = $topProducts->pluck('name')->toArray();
        $stockData = $topProducts->pluck('stock')->toArray();

        // Admin-specific transaction counts for the period
        $incomingTransactions = $this->dashboardRepository->getIncomingTransactionsCountInPeriod($startDate, $endDate);
        $outgoingTransactions = $this->dashboardRepository->getOutgoingTransactionsCountInPeriod($startDate, $endDate);

        // Chart data for the period
        $dateRange = $startDate->daysUntil($endDate);
        $transactionLabels = [];
        $incomingTransactionData = [];
        $outgoingTransactionData = [];
        $combinedTransactionData = [];

        foreach ($dateRange as $date) {
            $formattedDate = $date->format('Y-m-d');
            $transactionLabels[] = $date->format('d M'); // Using 'd M' for chart labels

            $incomingCount = $this->dashboardRepository->getTransactionsCountByDate($formattedDate, 'Masuk', 'Diterima');
            $outgoingCount = $this->dashboardRepository->getTransactionsCountByDate($formattedDate, 'Keluar', 'Diterima');

            $incomingTransactionData[] = $incomingCount;
            $outgoingTransactionData[] = $outgoingCount;
            $combinedTransactionData[] = $incomingCount + $outgoingCount;
        }

        // Recent activities
        $recentActivities = $this->dashboardRepository->getTodayActivitiesPaginated(10);

        return compact(
            'startDate', 'endDate',
            'totalUsers', 'activeUsers', 'totalSuppliers',
            'totalProducts', 'lowStockItems', 'availableStock', 'outOfStock',
            'incomingTransactions', 'outgoingTransactions',
            'transactionLabels', 'incomingTransactionData', 'outgoingTransactionData', 'combinedTransactionData',
            'stockLabels', 'stockData', // Now these variables exist!
            'recentActivities'
        );
    }

    /**
     * Get data specific to the Warehouse Manager dashboard.
     */
    protected function getWarehouseManagerDashboardData(): array
    {
        // Manager-specific transaction counts (today only)
        $todayIncomingTransactions = $this->dashboardRepository->getTodayIncomingTransactionsCount();
        $todayOutgoingTransactions = $this->dashboardRepository->getTodayOutgoingTransactionsCount();

        // Pending tasks for manager approval (limit 5 for dashboard overview)
        // Assuming 'Confirmed' status is what manager needs to approve. Adjust if 'Pending' is for manager.
        $pendingIncomingTasks = $this->dashboardRepository->getManagerPendingIncomingTransactions(5);
        $pendingOutgoingTasks = $this->dashboardRepository->getManagerPendingOutgoingTransactions(5);

        // Common product metrics (used by Admin and Warehouse Manager)
        $totalProducts = $this->dashboardRepository->getProductCount();
        $lowStockItems = $this->dashboardRepository->getLowStockCount();
        $availableStock = $this->dashboardRepository->getAvailableStockCount();
        $outOfStock = $this->dashboardRepository->getOutOfStockCount();

        return compact(
            'todayIncomingTransactions', 'todayOutgoingTransactions',
            'pendingIncomingTasks', 'pendingOutgoingTasks',
            'totalProducts', 'lowStockItems', 'availableStock', 'outOfStock'
        );
    }

    /**
     * Get data specific to the Warehouse Staff dashboard.
     */
    protected function getWarehouseStaffDashboardData(Request $request): array
    {
        // Staff-specific tasks (paginated)
        // Pass current page for each paginator using specific query parameters (e.g., incoming_page)
        $incomingTaskStaff = $this->dashboardRepository->getIncomingTasksPaginated(5, $request->input('incoming_page', 1), 'incoming_page');
        $outgoingTaskStaff = $this->dashboardRepository->getOutgoingTasksPaginated(5, $request->input('outgoing_page', 1), 'outgoing_page');
        $completeTaskStaff = $this->dashboardRepository->getCompletedTasksPaginated(5, $request->input('complete_page', 1), 'complete_page');

        // Since the prompt shows counts, provide counts for info section
        $pendingIncomingTasksCount = $incomingTaskStaff->total();
        $pendingOutgoingTasksCount = $outgoingTaskStaff->total();

        return compact(
            'incomingTaskStaff', 'outgoingTaskStaff', 'completeTaskStaff',
            'pendingIncomingTasksCount', 'pendingOutgoingTasksCount'
        );
    }
}