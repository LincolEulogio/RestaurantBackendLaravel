<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Category;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        $data = $this->getReportData($request);

        return view('reports.index', $data);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getReportData($request);

        return Excel::download(new ReportsExport($data), 'reporte-ventas-'.now()->format('d-m-Y').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = Pdf::loadView('reports.pdf', $data);

        return $pdf->download('reporte-ventas-'.now()->format('d-m-Y').'.pdf');
    }

    public function print(Request $request)
    {
        $data = $this->getReportData($request);

        return view('reports.print', $data);
    }

    private function getReportData(Request $request): array
    {
        // Date range (default: last 30 days)
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate)->startOfDay();
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Key Metrics
        $totalRevenue = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->sum('total');

        $completedOrders = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->count();

        $averageTicket = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0;

        $uniqueCustomers = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->distinct('customer_email')
            ->count('customer_email');

        // Previous period for comparison
        $daysDiff = $startDate->diffInDays($endDate);
        if ($daysDiff == 0) {
            $daysDiff = 1;
        }
        $prevStartDate = (clone $startDate)->subDays($daysDiff);
        $prevEndDate = (clone $startDate)->subSecond();

        $prevRevenue = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$prevStartDate, $prevEndDate])
            ->sum('total');

        $prevOrders = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$prevStartDate, $prevEndDate])
            ->count();

        $prevCustomers = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$prevStartDate, $prevEndDate])
            ->distinct('customer_email')
            ->count('customer_email');

        // Calculate percentage changes
        $revenueChange = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $ordersChange = $prevOrders > 0 ? (($completedOrders - $prevOrders) / $prevOrders) * 100 : 0;
        $ticketChange = $prevOrders > 0 ? (($averageTicket - ($prevRevenue / $prevOrders)) / ($prevRevenue / $prevOrders)) * 100 : 0;
        $customersChange = $prevCustomers > 0 ? (($uniqueCustomers - $prevCustomers) / $prevCustomers) * 100 : 0;

        // Monthly Revenue Data (last 12 months)
        $monthlyRevenue = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', Carbon::now()->subMonths(12))
            ->select(
                DB::raw('DATE_FORMAT(delivered_at, "%Y-%m") as month'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top Selling Products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.delivered_at', [$startDate, $endDate])
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Hourly Sales Distribution
        $hourlySales = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->select(
                DB::raw('HOUR(delivered_at) as hour'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        // Fill missing hours with zeros
        $hourlyData = collect(range(0, 23))->map(function ($hour) use ($hourlySales) {
            return [
                'hour' => $hour,
                'revenue' => $hourlySales->get($hour)->revenue ?? 0,
                'orders' => $hourlySales->get($hour)->orders ?? 0,
            ];
        });

        // Category Performance
        $categoryPerformance = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.delivered_at', [$startDate, $endDate])
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_sales'),
                DB::raw('SUM(order_items.subtotal) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // Calculate margins (simplified - assuming 30% cost)
        $categoryPerformance = $categoryPerformance->map(function ($category) {
            $category->margin = 70; // Simplified margin calculation

            return $category;
        });

        // Payment Methods Breakdown
        $paymentMethods = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('payment_method')
            ->get();

        // Order Types Distribution
        $orderTypes = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->select(
                'order_type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('order_type')
            ->get();

        // Daily Trends (last 30 days)
        $dailyTrends = Order::where('status', 'delivered')
            ->where('delivered_at', '>=', Carbon::now()->subDays(30))
            ->select(
                DB::raw('DATE(delivered_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Peak Hours Analysis
        $peakHours = Order::where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->select(
                DB::raw('HOUR(delivered_at) as hour'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('hour')
            ->orderByDesc('order_count')
            ->limit(3)
            ->get();

        // New Metrics
        $cancelledOrders = Order::where('status', 'cancelled')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();

        $cancelledAmount = Order::where('status', 'cancelled')
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->sum('total');

        $totalItemsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'delivered')
            ->whereBetween('orders.delivered_at', [$startDate, $endDate])
            ->sum('quantity');

        $cashRevenue = $paymentMethods->where('payment_method', 'cash')->sum('revenue');
        $cardRevenue = $paymentMethods->where('payment_method', 'card')->sum('revenue');
        $dineInOrders = $orderTypes->where('order_type', 'dine_in')->first()->count ?? 0;
        $takeawayOrders = $orderTypes->where('order_type', 'takeaway')->first()->count ?? 0;
        $deliveryOrders = $orderTypes->where('order_type', 'delivery')->first()->count ?? 0;

        $netProfit = $totalRevenue * 0.20;

        return [
            'totalRevenue' => $totalRevenue,
            'completedOrders' => $completedOrders,
            'averageTicket' => $averageTicket,
            'uniqueCustomers' => $uniqueCustomers,
            'revenueChange' => $revenueChange,
            'ordersChange' => $ordersChange,
            'ticketChange' => $ticketChange,
            'customersChange' => $customersChange,
            'monthlyRevenue' => $monthlyRevenue,
            'topProducts' => $topProducts,
            'hourlyData' => $hourlyData,
            'categoryPerformance' => $categoryPerformance,
            'paymentMethods' => $paymentMethods,
            'orderTypes' => $orderTypes,
            'dailyTrends' => $dailyTrends,
            'peakHours' => $peakHours,
            'cashRevenue' => $cashRevenue,
            'cardRevenue' => $cardRevenue,
            'dineInOrders' => $dineInOrders,
            'takeawayOrders' => $takeawayOrders,
            'deliveryOrders' => $deliveryOrders,
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'cancelledOrders' => $cancelledOrders,
            'cancelledAmount' => $cancelledAmount,
            'totalItemsSold' => $totalItemsSold ?? 0,
            'netProfit' => $netProfit,
        ];
    }
}
