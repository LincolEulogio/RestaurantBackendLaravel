<!-- Admin Dashboard - Full Access -->

<!-- Metrics Overview - Row 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Metric 1: Ventas del día -->
    <div class="bg-white dark:bg-gray-800  rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <i data-lucide="circle-dollar-sign" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $salesChange >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $salesChange >= 0 ? '+' : '' }}{{ number_format($salesChange, 1) }}%
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas del día</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($todaySales) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $todayOrders }} órdenes completadas</p>
        </div>
    </div>

    <!-- Metric 2: Pedidos activos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                <i data-lucide="clock" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                {{ $kitchenOrders }} en cocina
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pedidos activos</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En proceso</p>
        </div>
    </div>

    <!-- Metric 3: Mesas ocupadas -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                <i data-lucide="armchair" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                {{ number_format($tableOccupancy, 0) }}%
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mesas ocupadas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $occupiedTables }}/{{ $totalTables }}</p>
            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $tableOccupancy }}%"></div>
            </div>
        </div>
    </div>

    <!-- Metric 4: Stock bajo -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-red-50 rounded-lg text-red-600">
                <i data-lucide="alert-triangle" class="w-8 h-8"></i>
            </div>
            @if ($lowStockCount > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                    Crítico
                </span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                    OK
                </span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock bajo</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowStockCount }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Productos con stock < 10</p>
        </div>
    </div>
</div>

<!-- Additional Metrics Row 2 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Metric 5: Monthly Revenue -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600">
                <i data-lucide="trending-up" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $monthlyChange >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $monthlyChange >= 0 ? '+' : '' }}{{ number_format($monthlyChange, 1) }}%
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ingresos del Mes</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($thisMonthSales) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">vs mes anterior</p>
        </div>
    </div>

    <!-- Metric 6: Average Order Value -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-cyan-50 rounded-lg text-cyan-600">
                <i data-lucide="receipt" class="w-8 h-8"></i>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ticket Promedio</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($averageOrderValue) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Por orden hoy</p>
        </div>
    </div>

    <!-- Metric 7: Total Customers -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                <i data-lucide="users" class="w-8 h-8"></i>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Clientes</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalCustomers) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Clientes únicos</p>
        </div>
    </div>

    <!-- Metric 8: Platos Vendidos Today -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-pink-50 rounded-lg text-pink-600">
                <i data-lucide="utensils-crossed" class="w-8 h-8"></i>
            </div>
            @if ($pendingOrders > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $pendingOrders }} pendientes
                </span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Platos Vendidos</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($productsSoldToday) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Unidades hoy</p>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Weekly Sales Chart -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 lg:col-span-2">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Ventas Semanales</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">Últimos 7 días</span>
        </div>
        <div class="h-64 w-full">
            <canvas id="weeklySalesChart"></canvas>
        </div>
    </div>

    <!-- Top Dishes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Platos Más Vendidos</h3>
            <a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Ver
                todo</a>
        </div>
        <div class="space-y-4">
            @forelse($topProducts as $product)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-200">{{ $product->name }}</span>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ $product->total_quantity }}
                            vendidos</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2.5 rounded-full"
                            style="width: {{ ($product->total_quantity / $maxQuantity) * 100 }}%"></div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        {{ formatMoney($product->total_revenue) }} en ventas</div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Order Types & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Types Breakdown -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Tipos de Pedido (Hoy)</h3>
        </div>
        <div class="space-y-4">
            @php
                $totalTypeRevenue = $revenueByType->sum('revenue');
                $typeColors = [
                    'dine_in' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'label' => 'En Mesa'],
                    'delivery' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'label' => 'Delivery'],
                    'pickup' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'label' => 'Para Llevar'],
                ];
            @endphp
            @forelse($revenueByType as $type)
                @php
                    $percentage = $totalTypeRevenue > 0 ? ($type->revenue / $totalTypeRevenue) * 100 : 0;
                    $color = $typeColors[$type->order_type] ?? [
                        'bg' => 'bg-gray-500',
                        'text' => 'text-gray-600 dark:text-gray-300',
                        'label' => ucfirst($type->order_type),
                    ];
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="{{ $color['text'] }} font-medium">{{ $color['label'] }}</span>
                        <span
                            class="text-gray-900 dark:text-white font-semibold">{{ formatMoney($type->revenue) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5">
                            <div class="{{ $color['bg'] }} h-2.5 rounded-full" style="width: {{ $percentage }}%">
                            </div>
                        </div>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 min-w-[3rem] text-right">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $type->count }} órdenes</div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Activity -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden lg:col-span-2">
        <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Actividad Reciente</h3>
            <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Ver
                todo</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-semibold">Pedido</th>
                        <th class="px-6 py-3 font-semibold">Mesa/Tipo</th>
                        <th class="px-6 py-3 font-semibold">Cliente</th>
                        <th class="px-6 py-3 font-semibold">Estado</th>
                        <th class="px-6 py-3 font-semibold">Total</th>
                        <th class="px-6 py-3 font-semibold">Fecha/Hora</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-900 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ ucfirst($order->order_type) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $order->customer_name ?? 'Cliente' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'preparing' => 'bg-orange-100 text-orange-800',
                                        'ready' => 'bg-purple-100 text-purple-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pendiente',
                                        'confirmed' => 'Confirmado',
                                        'preparing' => 'En cocina',
                                        'ready' => 'Listo',
                                        'delivered' => 'Entregado',
                                        'cancelled' => 'Cancelado',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:text-gray-100' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">
                                {{ formatMoney($order->total) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $order->created_at->format('g:i A') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No hay órdenes recientes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Pass data to JavaScript
        window.dashboardData = {
            weeklySales: @json($weeklyData),
        };

        // Initialize Chart.js
        document.addEventListener('DOMContentLoaded', function() {
            // Weekly Sales Chart
            const weeklySalesCanvas = document.getElementById('weeklySalesChart');
            if (weeklySalesCanvas) {
                const ctx = weeklySalesCanvas.getContext('2d');

                const labels = window.dashboardData.weeklySales.map(item => {
                    const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                    const date = new Date(item.date);
                    return days[date.getDay()];
                });

                const data = window.dashboardData.weeklySales.map(item => parseFloat(item.revenue));

                let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, "rgba(59, 130, 246, 0.3)");
                gradient.addColorStop(1, "rgba(59, 130, 246, 0)");

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ventas',
                            data: data,
                            borderColor: '#3b82f6',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return 'Ventas: S/ ' + context.parsed.y.toLocaleString(
                                            'es-PE', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9',
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: (value) => {
                                        if (value >= 1000) {
                                            return 'S/ ' + (value / 1000).toFixed(0) + 'k';
                                        }
                                        return 'S/ ' + value;
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
