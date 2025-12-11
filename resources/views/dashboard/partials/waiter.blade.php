<!-- Waiter Dashboard - Personal Orders & Performance -->

<!-- Metrics Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- My Orders Today -->
    <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis Órdenes Hoy</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $myOrdersToday }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Órdenes creadas</p>
        </div>
    </div>

    <!-- My Sales Today -->
    <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis Ventas Hoy</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($mySalesToday) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total vendido</p>
        </div>
    </div>

    <!-- Active Orders -->
    <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Órdenes Activas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $myActiveOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En proceso</p>
        </div>
    </div>

    <!-- My Tables -->
    <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mis Mesas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $myTables }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Mesas activas</p>
        </div>
    </div>
</div>

<!-- Weekly Performance & Recent Orders -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Weekly Performance Chart -->
    <div class="bg-white dark:bg-gray-800 dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Mi Rendimiento Semanal</h3>
            <span class="text-sm text-gray-500 dark:text-gray-400">Últimos 7 días</span>
        </div>
        <div class="h-64 w-full">
            <canvas id="waiterWeeklyChart"></canvas>
        </div>
    </div>

    <!-- My Recent Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Mis Órdenes Recientes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-semibold">Pedido</th>
                        <th class="px-6 py-3 font-semibold">Cliente</th>
                        <th class="px-6 py-3 font-semibold">Estado</th>
                        <th class="px-6 py-3 font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 dark:bg-gray-900 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
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
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Pendiente',
                                        'confirmed' => 'Confirmado',
                                        'preparing' => 'En cocina',
                                        'ready' => 'Listo',
                                        'delivered' => 'Entregado',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:text-gray-100' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ formatMoney($order->total) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
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
        window.waiterData = {
            weeklySales: @json($weeklyData),
        };

        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('waiterWeeklyChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                const labels = window.waiterData.weeklySales.map(item => {
                    const days = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                    const date = new Date(item.date);
                    return days[date.getDay()];
                });
                const data = window.waiterData.weeklySales.map(item => parseFloat(item.revenue));

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Mis Ventas',
                            data: data,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
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
                                callbacks: {
                                    label: (context) => 'S/ ' + context.parsed.y.toLocaleString('es-PE', {
                                        minimumFractionDigits: 2
                                    })
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => 'S/ ' + value
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush

