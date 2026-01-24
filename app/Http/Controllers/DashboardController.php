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

        $dateRaw = DB::getDriverName() === 'sqlite' ? 'date(delivered_at)' : 'DATE(delivered_at)';
        $dayNameRaw = DB::getDriverName() === 'sqlite' 
            ? 'strftime("%w", delivered_at)' // Returns 0-6
            : 'DAYNAME(delivered_at)';

        $weeklySales = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw("$dateRaw as date"),
                DB::raw("$dayNameRaw as day_name"),
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

        $hourRaw = config('database.default') === 'sqlite' ? 'strftime("%H", delivered_at)' : 'HOUR(delivered_at)';

        $hourlySales = Order::where('status', 'delivered')
            ->whereDate('delivered_at', Carbon::today())
            ->select(
                DB::raw("$hourRaw as hour"),
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
            
        $delayedOrdersRaw = DB::getDriverName() === 'sqlite'
            ? '(strftime("%s", delivered_at) - strftime("%s", confirmed_at)) / 60 > 40'
            : 'TIMESTAMPDIFF(MINUTE, confirmed_at, delivered_at) > 40';

        $delayedOrdersCount = Order::where('status', 'delivered')
            ->whereDate('created_at', Carbon::today())
            ->whereRaw($delayedOrdersRaw)
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

        // ========== NUEVAS MÉTRICAS AVANZADAS ==========

        // 1. EFICIENCIA OPERATIVA
        
        // Tasa de rotación de mesas (cuántas veces se usa cada mesa por día)
        $dineInDeliveredToday = Order::where('order_type', 'dine_in')
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'delivered')
            ->count();
        $tableRotation = $totalTables > 0 ? round($dineInDeliveredToday / $totalTables, 2) : 0;

        // Tiempo promedio de atención completa (desde creación hasta entrega)
        $completedOrdersToday = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'delivered')
            ->whereNotNull('delivered_at')
            ->get();

        $avgServiceTime = $completedOrdersToday->count() > 0
            ? round($completedOrdersToday->average(function ($order) {
                return $order->delivered_at->diffInMinutes($order->created_at);
            }), 1)
            : 0;

        // Tasa de cancelación
        $totalOrdersToday = Order::whereDate('created_at', Carbon::today())->count();
        $cancelledToday = Order::whereDate('created_at', Carbon::today())
            ->where('status', 'cancelled')
            ->count();
        $cancellationRate = $totalOrdersToday > 0 
            ? round(($cancelledToday / $totalOrdersToday) * 100, 1) 
            : 0;

        // Eficiencia de cocina (órdenes completadas por hora)
        $currentHour = Carbon::now()->hour;
        $openingHour = 6; // Asumiendo apertura a las 6am
        $hoursOpen = max($currentHour - $openingHour, 1);
        $kitchenEfficiency = round($completedOrdersToday->count() / $hoursOpen, 1);

        // 2. ANÁLISIS DE CLIENTES

        // Clientes nuevos hoy (primera orden)
        $ordersToday = Order::whereDate('created_at', Carbon::today())
            ->whereNotNull('customer_email')
            ->get();

        $newCustomersToday = $ordersToday->filter(function ($order) {
            return Order::where('customer_email', $order->customer_email)
                ->where('created_at', '<', Carbon::today())
                ->doesntExist();
        })->unique('customer_email')->count();

        // Clientes recurrentes (con más de 1 orden histórica)
        $returningCustomers = DB::table('orders')
            ->select('customer_email', DB::raw('COUNT(*) as order_count'))
            ->whereNotNull('customer_email')
            ->groupBy('customer_email')
            ->having('order_count', '>', 1)
            ->count();

        // Distribución de órdenes por hora (para identificar horarios pico)
        $hourRawForOrders = DB::getDriverName() === 'sqlite' 
            ? 'strftime("%H", created_at)' 
            : 'HOUR(created_at)';

        $ordersByHour = Order::whereDate('created_at', Carbon::today())
            ->select(
                DB::raw("$hourRawForOrders as hour"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Horario pico del día
        $peakHour = $ordersByHour->sortByDesc('count')->first();
        $peakHourTime = $peakHour ? $peakHour->hour . ':00' : 'N/A';
        $peakHourOrders = $peakHour ? $peakHour->count : 0;

        // 3. MÉTRICAS FINANCIERAS AVANZADAS

        // Proyección de ventas del mes (basada en promedio diario)
        $daysInMonth = Carbon::now()->daysInMonth;
        $daysPassed = Carbon::now()->day;
        $avgDailySales = $daysPassed > 0 
            ? Order::where('status', 'delivered')
                ->whereMonth('delivered_at', Carbon::now()->month)
                ->whereYear('delivered_at', Carbon::now()->year)
                ->sum('total') / $daysPassed
            : 0;
        $projectedMonthlySales = round($avgDailySales * $daysInMonth, 2);

        // Margen de ganancia estimado (asumiendo 60% de margen)
        $estimatedProfit = round($todaySales * 0.6, 2);
        $estimatedCost = round($todaySales * 0.4, 2);

        // 4. ANÁLISIS DE PERSONAL MEJORADO

        // Productividad de meseros (órdenes y ventas por mesero)
        $waiterProductivity = DB::table('orders')
            ->join('users', 'orders.waiter_id', '=', 'users.id')
            ->whereDate('orders.created_at', Carbon::today())
            ->select(
                'users.name',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total) as total_sales'),
                DB::raw('AVG(orders.total) as avg_ticket'),
                DB::raw('COUNT(CASE WHEN orders.status = "delivered" THEN 1 END) as completed_orders')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Tiempo promedio de servicio por mesero
        $waiterServiceTimeRaw = DB::getDriverName() === 'sqlite'
            ? '(strftime("%s", orders.delivered_at) - strftime("%s", orders.created_at)) / 60'
            : 'TIMESTAMPDIFF(MINUTE, orders.created_at, orders.delivered_at)';

        $waiterServiceTime = DB::table('orders')
            ->join('users', 'orders.waiter_id', '=', 'users.id')
            ->whereDate('orders.created_at', Carbon::today())
            ->where('orders.status', 'delivered')
            ->whereNotNull('orders.delivered_at')
            ->select(
                'users.name',
                DB::raw("AVG($waiterServiceTimeRaw) as avg_time")
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('avg_time')
            ->limit(5)
            ->get();

        // 5. PREDICCIONES Y TENDENCIAS

        // Predicción simple de ventas para mañana (basada en mismo día de semana pasada)
        $tomorrowDayOfWeek = Carbon::tomorrow()->dayOfWeek;
        $lastWeekSameDay = Carbon::now()->subWeek()->startOfWeek()->addDays($tomorrowDayOfWeek);
        
        $lastWeekSameDaySales = Order::where('status', 'delivered')
            ->whereDate('delivered_at', $lastWeekSameDay)
            ->sum('total');
        
        $predictedTomorrowSales = round($lastWeekSameDaySales, 2);

        // Productos que necesitarán restock pronto
        $productsNeedingRestock = InventoryItem::where(function($query) {
                $query->where('stock_current', '<', DB::raw('stock_min'))
                      ->orWhereRaw('stock_current < (stock_min * 1.5)');
            })
            ->where('is_active', true)
            ->orderBy('stock_current')
            ->limit(10)
            ->get();

        // 6. MÉTRICAS DE CALIDAD

        // Tiempo promedio de preparación por categoría
        $prepTimeRaw = DB::getDriverName() === 'sqlite'
            ? '(strftime("%s", ready_at) - strftime("%s", confirmed_at)) / 60'
            : 'TIMESTAMPDIFF(MINUTE, confirmed_at, ready_at)';

        $prepTimeByCategory = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereDate('orders.created_at', Carbon::today())
            ->where('orders.status', 'delivered')
            ->whereNotNull('orders.confirmed_at')
            ->whereNotNull('orders.ready_at')
            ->select(
                'categories.name',
                DB::raw("AVG($prepTimeRaw) as avg_prep_time")
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('avg_prep_time')
            ->get();

        // Tasa de satisfacción estimada (clientes que reordenan)
        $uniqueCustomers = Order::whereNotNull('customer_email')
            ->distinct('customer_email')
            ->count('customer_email');
        
        $returningCustomersCount = DB::table('orders')
            ->whereNotNull('customer_email')
            ->select('customer_email')
            ->groupBy('customer_email')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->count();

        $satisfactionRate = $uniqueCustomers > 0 
            ? round(($returningCustomersCount / $uniqueCustomers) * 100, 1) 
            : 0;

        // Órdenes perfectas (entregadas en menos de 30 minutos)
        $perfectOrdersRaw = DB::getDriverName() === 'sqlite'
            ? '(strftime("%s", orders.delivered_at) - strftime("%s", orders.created_at)) / 60 <= 30'
            : 'TIMESTAMPDIFF(MINUTE, orders.created_at, orders.delivered_at) <= 30';

        $perfectOrders = Order::where('status', 'delivered')
            ->whereDate('created_at', Carbon::today())
            ->whereNotNull('delivered_at')
            ->whereRaw($perfectOrdersRaw)
            ->count();

        $perfectOrderRate = $completedOrdersToday->count() > 0
            ? round(($perfectOrders / $completedOrdersToday->count()) * 100, 1)
            : 0;

        // 7. INSIGHTS DE IA MEJORADOS

        // Insight de horario pico
        if ($peakHourOrders > 10) {
            $aiInsights[] = [
                'type' => 'info',
                'icon' => 'clock',
                'text' => "Horario pico hoy: {$peakHourTime} hrs con {$peakHourOrders} órdenes. Asegura personal suficiente."
            ];
        }

        // Insight de rotación de mesas
        if ($tableRotation < 2 && $totalTables > 0) {
            $aiInsights[] = [
                'type' => 'warning',
                'icon' => 'table',
                'text' => "Rotación de mesas baja ({$tableRotation} veces/día). Optimiza tiempos de servicio."
            ];
        } elseif ($tableRotation > 4) {
            $aiInsights[] = [
                'type' => 'good',
                'icon' => 'trending-up',
                'text' => "Excelente rotación de mesas ({$tableRotation} veces/día). ¡Gran eficiencia!"
            ];
        }

        // Insight de clientes nuevos
        if ($newCustomersToday > 5) {
            $aiInsights[] = [
                'type' => 'good',
                'icon' => 'users',
                'text' => "{$newCustomersToday} clientes nuevos hoy. ¡Excelente adquisición!"
            ];
        }

        // Insight de proyección
        if ($projectedMonthlySales > $lastMonthSales && $lastMonthSales > 0) {
            $projectionDiff = $projectedMonthlySales - $lastMonthSales;
            $aiInsights[] = [
                'type' => 'good',
                'icon' => 'trending-up',
                'text' => "Proyección: S/. " . number_format($projectedMonthlySales, 2) . " este mes (+" . number_format($projectionDiff, 2) . " vs mes pasado)"
            ];
        }

        // Insight de eficiencia
        if ($perfectOrderRate > 70) {
            $aiInsights[] = [
                'type' => 'good',
                'icon' => 'check-circle',
                'text' => "{$perfectOrderRate}% de órdenes entregadas en <30 min. ¡Excelente servicio!"
            ];
        } elseif ($perfectOrderRate < 40 && $completedOrdersToday->count() > 5) {
            $aiInsights[] = [
                'type' => 'warning',
                'icon' => 'alert-triangle',
                'text' => "Solo {$perfectOrderRate}% de órdenes en <30 min. Revisa procesos de cocina."
            ];
        }

        // Insight de cancelaciones
        if ($cancellationRate > 10 && $totalOrdersToday > 5) {
            $aiInsights[] = [
                'type' => 'bad',
                'icon' => 'x-circle',
                'text' => "Tasa de cancelación alta ({$cancellationRate}%). Investiga causas."
            ];
        }

        // Insight de satisfacción
        if ($satisfactionRate > 50) {
            $aiInsights[] = [
                'type' => 'good',
                'icon' => 'heart',
                'text' => "{$satisfactionRate}% de clientes regresan. ¡Gran fidelización!"
            ];
        }

        return view('dashboard', compact(
            // Métricas básicas
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
            'lastMonthSales',
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
            'avgTicketByChannel',
            
            // Nuevas métricas de eficiencia operativa
            'tableRotation',
            'avgServiceTime',
            'cancellationRate',
            'kitchenEfficiency',
            
            // Nuevas métricas de análisis de clientes
            'newCustomersToday',
            'returningCustomers',
            'ordersByHour',
            'peakHourTime',
            'peakHourOrders',
            
            // Nuevas métricas financieras avanzadas
            'projectedMonthlySales',
            'estimatedProfit',
            'estimatedCost',
            
            // Nuevas métricas de personal
            'waiterProductivity',
            'waiterServiceTime',
            
            // Nuevas predicciones y tendencias
            'predictedTomorrowSales',
            'productsNeedingRestock',
            
            // Nuevas métricas de calidad
            'prepTimeByCategory',
            'satisfactionRate',
            'perfectOrders',
            'perfectOrderRate'
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

        $hourRaw = DB::getDriverName() === 'sqlite' ? 'strftime("%H", delivered_at)' : 'HOUR(delivered_at)';

        $hourlyData = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select(
                DB::raw("$hourRaw as hour"),
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

        $hourRaw = config('database.default') === 'sqlite' ? 'strftime("%H", delivered_at)' : 'HOUR(delivered_at)';

        $hourlyData = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$todayStart, $todayEnd])
            ->select(
                DB::raw("$hourRaw as hour"),
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

    /**
     * Filter sales chart data via AJAX
     */
    public function filterSalesChart(Request $request)
    {
        $timeFilter = $request->input('timeFilter', 'week');
        
        $labels = [];
        $data = [];
        
        switch ($timeFilter) {
            case 'hour':
                // Last 60 minutes, grouped by 5-minute intervals
                $startTime = Carbon::now()->subHour();
                $endTime = Carbon::now();
                
                $minuteRaw = DB::getDriverName() === 'sqlite'
                    ? '(CAST(strftime("%M", delivered_at) AS INTEGER) / 5) * 5'
                    : '(MINUTE(delivered_at) DIV 5) * 5';
                
                $hourRaw = DB::getDriverName() === 'sqlite'
                    ? 'strftime("%H", delivered_at)'
                    : 'HOUR(delivered_at)';
                
                $sales = Order::where('status', 'delivered')
                    ->whereBetween('delivered_at', [$startTime, $endTime])
                    ->select(
                        DB::raw("$hourRaw as hour"),
                        DB::raw("$minuteRaw as minute"),
                        DB::raw('SUM(total) as revenue')
                    )
                    ->groupBy('hour', 'minute')
                    ->orderBy('hour')
                    ->orderBy('minute')
                    ->get();
                
                // Fill all 5-minute intervals
                for ($i = 0; $i < 12; $i++) {
                    $time = Carbon::now()->subMinutes(60 - ($i * 5));
                    $hour = $time->format('H');
                    $minute = (int)($time->format('i') / 5) * 5;
                    
                    $existing = $sales->first(function($item) use ($hour, $minute) {
                        return $item->hour == $hour && $item->minute == $minute;
                    });
                    
                    $labels[] = $time->format('H:i');
                    $data[] = $existing ? (float)$existing->revenue : 0;
                }
                break;
                
            case 'today':
                // Last 24 hours, grouped by hour
                $startTime = Carbon::now()->subDay();
                $endTime = Carbon::now();
                
                $hourRaw = DB::getDriverName() === 'sqlite'
                    ? 'strftime("%H", delivered_at)'
                    : 'HOUR(delivered_at)';
                
                $sales = Order::where('status', 'delivered')
                    ->whereBetween('delivered_at', [$startTime, $endTime])
                    ->select(
                        DB::raw("$hourRaw as hour"),
                        DB::raw('SUM(total) as revenue')
                    )
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();
                
                // Fill all 24 hours
                for ($i = 23; $i >= 0; $i--) {
                    $hour = Carbon::now()->subHours($i)->format('H');
                    $existing = $sales->firstWhere('hour', $hour);
                    
                    $labels[] = $hour . ':00';
                    $data[] = $existing ? (float)$existing->revenue : 0;
                }
                break;
                
            case 'week':
                // Last 7 days, grouped by day
                $dateRaw = DB::getDriverName() === 'sqlite' ? 'date(delivered_at)' : 'DATE(delivered_at)';
                
                $sales = Order::where('status', 'delivered')
                    ->where('delivered_at', '>=', Carbon::now()->subDays(7))
                    ->select(
                        DB::raw("$dateRaw as date"),
                        DB::raw('SUM(total) as revenue')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                
                // Fill all 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dateStr = $date->format('Y-m-d');
                    $existing = $sales->firstWhere('date', $dateStr);
                    
                    $labels[] = $date->locale('es')->isoFormat('ddd');
                    $data[] = $existing ? (float)$existing->revenue : 0;
                }
                break;
                
            case 'month':
                // Last 30 days, grouped by day
                $dateRaw = DB::getDriverName() === 'sqlite' ? 'date(delivered_at)' : 'DATE(delivered_at)';
                
                $sales = Order::where('status', 'delivered')
                    ->where('delivered_at', '>=', Carbon::now()->subDays(30))
                    ->select(
                        DB::raw("$dateRaw as date"),
                        DB::raw('SUM(total) as revenue')
                    )
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                
                // Fill all 30 days
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $dateStr = $date->format('Y-m-d');
                    $existing = $sales->firstWhere('date', $dateStr);
                    
                    $labels[] = $date->format('d M');
                    $data[] = $existing ? (float)$existing->revenue : 0;
                }
                break;
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'filter' => $timeFilter
        ]);
    }
}
