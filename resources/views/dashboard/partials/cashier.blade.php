<!-- Cashier Dashboard - Sales & Payments -->

<!-- Metrics Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Today's Sales -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas del Día</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($todaySales) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $todayOrders }} órdenes</p>
        </div>
    </div>

    <!-- Cash Payments -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Efectivo</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($cashToday) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pagos en efectivo</p>
        </div>
    </div>

    <!-- Card Payments -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tarjeta</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($cardToday) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pagos con tarjeta</p>
        </div>
    </div>

    <!-- Pending Payment -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-yellow-50 rounded-lg text-yellow-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes de Pago</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingPayment }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Órdenes listas</p>
        </div>
    </div>
</div>

<!-- Payment Methods & Recent Transactions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Payment Methods Breakdown -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Métodos de Pago</h3>
        </div>
        <div class="space-y-4">
            @php
                $totalPayments = $paymentMethodsToday->sum('revenue');
                $methodColors = [
                    'cash' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'label' => 'Efectivo'],
                    'card' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'label' => 'Tarjeta'],
                    'transfer' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'label' => 'Transferencia'],
                ];
            @endphp
            @forelse($paymentMethodsToday as $method)
                @php
                    $percentage = $totalPayments > 0 ? ($method->revenue / $totalPayments) * 100 : 0;
                    $color = $methodColors[$method->payment_method] ?? [
                        'bg' => 'bg-gray-500',
                        'text' => 'text-gray-600 dark:text-gray-300',
                        'label' => ucfirst($method->payment_method),
                    ];
                @endphp
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="{{ $color['text'] }} font-medium">{{ $color['label'] }}</span>
                        <span class="text-gray-900 dark:text-white font-semibold">{{ formatMoney($method->revenue) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5">
                            <div class="{{ $color['bg'] }} h-2.5 rounded-full" style="width: {{ $percentage }}%">
                            </div>
                        </div>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 min-w-[3rem] text-right">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $method->count }} transacciones</div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay datos disponibles</p>
            @endforelse
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden lg:col-span-2">
        <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Transacciones Recientes</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">
                        <th class="px-6 py-3 font-semibold">Pedido</th>
                        <th class="px-6 py-3 font-semibold">Cliente</th>
                        <th class="px-6 py-3 font-semibold">Método</th>
                        <th class="px-6 py-3 font-semibold">Total</th>
                        <th class="px-6 py-3 font-semibold">Hora</th>
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
                                    $methodBadges = [
                                        'cash' => 'bg-green-100 text-green-800',
                                        'card' => 'bg-purple-100 text-purple-800',
                                        'transfer' => 'bg-blue-100 text-blue-800',
                                    ];
                                    $methodLabels = [
                                        'cash' => 'Efectivo',
                                        'card' => 'Tarjeta',
                                        'transfer' => 'Transferencia',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $methodBadges[$order->payment_method] ?? 'bg-gray-100 text-gray-800 dark:text-gray-100' }}">
                                    {{ $methodLabels[$order->payment_method] ?? ucfirst($order->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-900 dark:text-white">{{ formatMoney($order->total) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->delivered_at->format('g:i A') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No hay transacciones recientes
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

