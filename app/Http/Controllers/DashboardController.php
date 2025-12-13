<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\Table;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Determine user role and get appropriate data
        if ($user->hasRole('admin') || $user->hasRole('gerente')) {
            return $this->getAdminDashboard();
        } elseif ($user->hasRole('waiter')) {
            return $this->getWaiterDashboard();
        } elseif ($user->hasRole('kitchen')) {
            return $this->getKitchenDashboard();
        } elseif ($user->hasRole('cashier')) {
            return $this->getCashierDashboard();
        }

        // Default fallback
        return $this->getAdminDashboard();
    }

    /**
     * Admin/Gerente Dashboard - Full system access
     */
    private function getAdminDashboard()
    {
        // Today's metrics
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();

        $todaySales = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->sum('total');

        $todayOrders = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->count();

        // Yesterday's sales for comparison
        $yesterdayStart = Carbon::yesterday();
        $yesterdayEnd = Carbon::yesterday()->endOfDay();

        $yesterdaySales = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$yesterdayStart, $yesterdayEnd])
            ->sum('total');

        $salesChange = $yesterdaySales > 0
            ? (($todaySales - $yesterdaySales) / $yesterdaySales) * 100
            : 0;

        // Active orders (pending, confirmed, preparing, ready)
        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->count();

        // Orders in kitchen
        $kitchenOrders = Order::whereIn('status', ['confirmed', 'preparing'])
            ->count();

        // Order types distribution (for table occupancy alternative)
        $dineInOrders = Order::where('order_type', 'dine_in')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->count();

        // Real table data
        $totalTables = Table::count();
        $occupiedTables = Table::where('status', 'occupied')->count();
        $tableOccupancy = $totalTables > 0 ? ($occupiedTables / $totalTables) * 100 : 0;

        // Today's Reservations
        $todayReservations = Reservation::whereDate('reservation_date', Carbon::today())
            ->where('status', '!=', 'cancelled')
            ->count();

        // Low stock items (stock_current < 10)
        $lowStockCount = InventoryItem::where('stock_current', '<', 10)->count();

        // Weekly sales (last 7 days)
        $weeklySales = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(delivered_at) as date'),
                DB::raw('DAYNAME(delivered_at) as day_name'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date', 'day_name')
            ->orderBy('date')
            ->get();

        // Fill missing days with zero
        $weeklyData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayName = Carbon::now()->subDays($i)->locale('es')->dayName;

            $existing = $weeklySales->firstWhere('date', $date);
            $weeklyData->push([
                'date' => $date,
                'day_name' => $dayName,
                'revenue' => $existing ? $existing->revenue : 0,
            ]);
        }

        // Top 5 selling products (by quantity)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'delivered')
            ->where('orders.delivered_at', '>=', Carbon::now()->subDays(30))
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Calculate max for percentage bars
        $maxQuantity = $topProducts->max('total_quantity') ?: 1;

        // Recent orders (last 10)
        $recentOrders = Order::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly comparison
        $thisMonthSales = Order::where('status', 'delivered')
            ->whereMonth('delivered_at', Carbon::now()->month)
            ->whereYear('delivered_at', Carbon::now()->year)
            ->sum('total');

        $lastMonthSales = Order::where('status', 'delivered')
            ->whereMonth('delivered_at', Carbon::now()->subMonth()->month)
            ->whereYear('delivered_at', Carbon::now()->subMonth()->year)
            ->sum('total');

        $monthlyChange = $lastMonthSales > 0
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // Additional Statistics

        // Average Order Value (today)
        $averageOrderValue = $todayOrders > 0 ? $todaySales / $todayOrders : 0;

        // Total Customers (unique customer names)
        $totalCustomers = Order::distinct('customer_name')
            ->whereNotNull('customer_name')
            ->count('customer_name');

        // Products sold today
        $productsSoldToday = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.delivered_at', [$todayStart, $todayEnd])
            ->sum('order_items.quantity');

        // Pending orders (need attention)
        $pendingOrders = Order::where('status', 'pending')->count();

        // Total inventory value
        $inventoryValue = InventoryItem::where('is_active', true)
            ->selectRaw('SUM(stock_current * price_unit) as total_value')
            ->value('total_value') ?? 0;

        // Top category today (by revenue)
        $topCategoryToday = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.delivered_at', [$todayStart, $todayEnd])
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->first();

        // Revenue by order type
        $revenueByType = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select('order_type', DB::raw('SUM(total) as revenue'), DB::raw('COUNT(*) as count'))
            ->groupBy('order_type')
            ->get();

        // Payment methods breakdown (today)
        $paymentMethodsToday = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get();

        // 2. Top Waiters (by revenue today)
        $topWaiters = DB::table('orders')
            ->join('users', 'orders.waiter_id', '=', 'users.id')
            ->whereDate('orders.created_at', Carbon::today())
            ->whereIn('orders.status', ['delivered', 'ready', 'confirmed']) // Count active succesful orders
            ->select(
                'users.name',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total) as total_sales')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // 3. Sales by Category (distribution)
        $salesByCategory = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.delivered_at', [$todayStart, $todayEnd])
            ->select('categories.name', DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // 4. Order Status Distribution (Active)
        $orderStatusStats = Order::whereDate('created_at', Carbon::today())
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // 5. Peak Hours (Hourly Sales Today)
        $hourlySales = Order::where('status', 'delivered')
            ->whereDate('delivered_at', Carbon::today())
            ->select(
                DB::raw('HOUR(delivered_at) as hour'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // 6. Detailed Inventory Stats
        $inventoryStats = [
            'critical' => InventoryItem::where('stock_current', '<', 5)->count(),
            'low' => InventoryItem::whereBetween('stock_current', [5, 10])->count(),
            'normal' => InventoryItem::where('stock_current', '>', 10)->count(),
        ];

        // 7. AI Insights (Rule-based)
        $aiInsights = [];
        
        // Sales Insight
        if ($salesChange > 10) $aiInsights[] = ['type' => 'good', 'icon' => 'trending-up', 'text' => "Ventas subieron {$salesChange}% vs ayer. ¡Buen trabajo!"];
        elseif ($salesChange < -10) $aiInsights[] = ['type' => 'bad', 'icon' => 'trending-down', 'text' => "Ventas bajaron " . abs(round($salesChange,1)) . "% vs ayer. Revisa promociones."];
        
        // Product Insight
        if ($topProducts->isNotEmpty()) {
            $bestDish = $topProducts->first();
            $aiInsights[] = ['type' => 'info', 'icon' => 'star', 'text' => "El plato estrella hoy es {$bestDish->name} con {$bestDish->total_quantity} ventas."];
        }

        // Staff Insight - Restore Real Kitchen Time Calculation
        $kitchenTimeData = Order::whereNotNull('confirmed_at')
            ->whereNotNull('ready_at')
            ->whereDate('created_at', Carbon::today())
            ->get();

        $kitchenTimeAvg = $kitchenTimeData->count() > 0
            ? $kitchenTimeData->average(function ($order) {
                return $order->ready_at->diffInMinutes($order->confirmed_at);
            })
            : 0;

        if ($kitchenTimeAvg > 20) $aiInsights[] = ['type' => 'warning', 'icon' => 'clock', 'text' => "Tiempo de cocina alto (" . round($kitchenTimeAvg, 1) . " min). Considera reforzar turno."];
        
        // Stock Insight
        if ($inventoryStats['critical'] > 0) $aiInsights[] = ['type' => 'danger', 'icon' => 'alert-triangle', 'text' => "¡Atención! {$inventoryStats['critical']} productos con stock crítico."];

        // --- NEW EXECUTIVE METRICS ---
        
        // 8. Dead Dishes (No sales in 30 days)
        // Find products that are NOT in order_items of orders from last 30 days
        $soldProductIds = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->pluck('product_id');
            
        $deadDishes = DB::table('products')
            ->whereNotIn('id', $soldProductIds)
            ->where('is_available', true) // Only count active products
            ->limit(5)
            ->get();
            
        // 9. Delayed Orders (> 40 mins from confirmed to delivered)
        $delayedOrdersCount = Order::where('status', 'delivered')
            ->whereDate('created_at', Carbon::today())
            ->whereRaw('TIMESTAMPDIFF(MINUTE, confirmed_at, delivered_at) > 40')
            ->count();

        // 10. Most Used Table
        $mostUsedTable = DB::table('orders')
            ->whereNotNull('table_id')
            ->where('created_at', '>=', Carbon::now()->subDays(30)) // Monthly stats
            ->select('table_id', DB::raw('COUNT(*) as use_count'))
            ->groupBy('table_id')
            ->orderByDesc('use_count')
            ->first();
            
        $mostUsedTableName = $mostUsedTable 
            ? Table::find($mostUsedTable->table_id)->table_number ?? 'Mesa ' . $mostUsedTable->table_id
            : 'N/A';

        // 11. Average Ticket by Channel (Dine-in vs Online)
        $avgTicketByChannel = Order::where('status', 'delivered')
             ->whereDate('created_at', Carbon::today())
             ->select('order_type', DB::raw('AVG(total) as avg_ticket'))
             ->groupBy('order_type')
             ->get();

        // Add Insights for these new metrics
        if ($delayedOrdersCount > 2) {
             $aiInsights[] = ['type' => 'bad', 'icon' => 'alert-circle', 'text' => "{$delayedOrdersCount} pedidos demoraron más de 40 min hoy. Revisa procesos."];
        }
        if ($deadDishes->count() > 0) {
             $aiInsights[] = ['type' => 'info', 'icon' => 'archive', 'text' => "Tienes " . $deadDishes->count() . " platos sin ventas en 30 días (ej. {$deadDishes->first()->name}). Considera rotarlos."];
        }

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'salesChange',
            'activeOrders',
            'kitchenOrders',
            'occupiedTables',
            'totalTables',
            'tableOccupancy',
            'lowStockCount',
            'weeklyData',
            'topProducts',
            'maxQuantity',
            'recentOrders',
            'thisMonthSales',
            'monthlyChange',
            'averageOrderValue',
            'totalCustomers',
            'productsSoldToday',
            'pendingOrders',
            'inventoryValue',
            'topCategoryToday',
            'revenueByType',
            'paymentMethodsToday',
            'todayReservations',
            'kitchenTimeAvg',
            'topWaiters',
            'salesByCategory',
            'orderStatusStats',
            'hourlySales',
            'inventoryStats',
            'aiInsights',
            'deadDishes',
            'delayedOrdersCount',
            'mostUsedTableName',
            'avgTicketByChannel'
        ))->with('userRole', 'admin');
    }

    /**
     * Waiter Dashboard - Personal orders and tables
     */
    private function getWaiterDashboard()
    {
        $user = Auth::user();
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();

        // Waiter's orders today
        $myOrdersToday = Order::where('user_id', $user->id)
            ->whereBetween('created_at', [$todayStart, $todayEnd])
            ->count();

        // Waiter's sales today
        $mySalesToday = Order::where('user_id', $user->id)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->sum('total');

        // Active orders assigned to waiter
        $myActiveOrders = Order::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->count();

        // Waiter's tables (dine-in orders)
        $myTables = Order::where('user_id', $user->id)
            ->where('order_type', 'dine_in')
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->count();

        // Recent orders for this waiter
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Weekly performance
        $weeklyData = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dayStart = Carbon::now()->subDays($i)->startOfDay();
            $dayEnd = Carbon::now()->subDays($i)->endOfDay();

            $revenue = Order::where('user_id', $user->id)
                ->where('status', 'delivered')
                ->whereBetween('delivered_at', [$dayStart, $dayEnd])
                ->sum('total');

            $weeklyData->push([
                'date' => $date,
                'day_name' => Carbon::now()->subDays($i)->locale('es')->dayName,
                'revenue' => $revenue,
            ]);
        }

        return view('dashboard', compact(
            'myOrdersToday',
            'mySalesToday',
            'myActiveOrders',
            'myTables',
            'recentOrders',
            'weeklyData'
        ))->with('userRole', 'waiter');
    }

    /**
     * Kitchen Dashboard - Orders in preparation
     */
    private function getKitchenDashboard()
    {
        // Orders pending preparation
        $pendingOrders = Order::where('status', 'pending')->count();

        // Orders in preparation
        $preparingOrders = Order::whereIn('status', ['confirmed', 'preparing'])->count();

        // Orders ready for delivery
        $readyOrders = Order::where('status', 'ready')->count();

        // Orders completed today
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();
        $completedToday = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->count();

        // Active orders queue
        $activeOrders = Order::whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->limit(20)
            ->get();

        // Hourly completion rate today
        $hourlyData = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select(
                DB::raw('HOUR(delivered_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('dashboard', compact(
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'completedToday',
            'activeOrders',
            'hourlyData'
        ))->with('userRole', 'kitchen');
    }

    /**
     * Cashier Dashboard - Sales and payments
     */
    private function getCashierDashboard()
    {
        $todayStart = Carbon::today();
        $todayEnd = Carbon::now();

        // Today's sales
        $todaySales = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->sum('total');

        // Today's orders
        $todayOrders = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->count();

        // Cash payments today
        $cashToday = Order::where('status', 'delivered')
            ->where('payment_method', 'cash')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->sum('total');

        // Card payments today
        $cardToday = Order::where('status', 'delivered')
            ->where('payment_method', 'card')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->sum('total');

        // Orders pending payment
        $pendingPayment = Order::where('status', 'ready')->count();

        // Payment methods breakdown
        $paymentMethodsToday = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as revenue'))
            ->groupBy('payment_method')
            ->get();

        // Recent transactions
        $recentOrders = Order::where('status', 'delivered')
            ->orderBy('delivered_at', 'desc')
            ->limit(15)
            ->get();

        // Hourly sales
        $hourlyData = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select(
                DB::raw('HOUR(delivered_at) as hour'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'todayOrders',
            'cashToday',
            'cardToday',
            'pendingPayment',
            'paymentMethodsToday',
            'recentOrders',
            'hourlyData'
        ))->with('userRole', 'cashier');
    }
}
