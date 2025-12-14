<x-app-layout>
    <div x-data="reportsManager()" class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reportes y Análisis</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Analiza el rendimiento de tu restaurante con
                    métricas detalladas
                </p>
            </div>
            <div class="flex items-center gap-2">
                <x-ui.button variant="outline"
                    class="gap-2 text-gray-600 border-gray-300 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Exportar PDF
                </x-ui.button>
                <x-ui.button class="gap-2 bg-emerald-500 hover:bg-emerald-600 text-white border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Exportar Excel
                </x-ui.button>
            </div>
        </div>

        <!-- Date Range Filter -->
        <x-ui.card class="px-5 py-3">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <h3 class="font-medium text-gray-900 dark:text-white">Rango de Fechas</h3>
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Desde:</span>
                        <input type="date" x-model="startDate"
                            class="border-gray-200 dark:border-gray-600 rounded-md text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-600 dark:text-gray-200 dark:bg-gray-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Hasta:</span>
                        <input type="date" x-model="endDate"
                            class="border-gray-200 dark:border-gray-600 rounded-md text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-600 dark:text-gray-200 dark:bg-gray-700">
                    </div>
                    <button @click="applyDateFilter()"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors dark:bg-blue-700 dark:hover:bg-blue-800">
                        Aplicar Filtro
                    </button>
                    <div class="flex bg-gray-100 dark:bg-gray-700 p-1 rounded-md">
                        <button @click="setQuickFilter(0)" class="px-3 py-1 text-sm rounded transition-all"
                            :class="startDate === endDate ?
                                'bg-white dark:bg-gray-600 shadow-sm text-gray-800 dark:text-white font-medium' :
                                'text-gray-600 dark:text-gray-300 hover:bg-white/50'">
                            Hoy
                        </button>
                        <button @click="setQuickFilter(7)" class="px-3 py-1 text-sm rounded transition-all"
                            :class="(new Date(endDate) - new Date(startDate)) / (1000 * 3600 * 24) === 7 ?
                                'bg-white dark:bg-gray-600 shadow-sm text-gray-800 dark:text-white font-medium' :
                                'text-gray-600 dark:text-gray-300 hover:bg-white/50'">
                            7 días
                        </button>
                        <button @click="setQuickFilter(30)" class="px-3 py-1 text-sm rounded transition-all"
                            :class="(new Date(endDate) - new Date(startDate)) / (1000 * 3600 * 24) === 30 ?
                                'bg-white dark:bg-gray-600 shadow-sm text-gray-800 dark:text-white font-medium' :
                                'text-gray-600 dark:text-gray-300 hover:bg-white/50'">
                            30 días
                        </button>
                    </div>
                </div>
            </div>
        </x-ui.card>

        <!-- Key Metrics - Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Metric 1: Total Revenue -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-500 dark:text-blue-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold {{ $revenueChange >= 0 ? 'text-green-500 bg-green-50' : 'text-red-500 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $revenueChange >= 0 ? '+' : '' }}{{ number_format($revenueChange, 1) }}%
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatMoney($totalRevenue) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresos Totales</p>
                </div>
            </x-ui.card>

            <!-- Metric 2: Completed Orders -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div
                        class="p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-emerald-500 dark:text-emerald-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold {{ $ordersChange >= 0 ? 'text-green-500 bg-green-50' : 'text-red-500 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $ordersChange >= 0 ? '+' : '' }}{{ number_format($ordersChange, 1) }}%
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($completedOrders) }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Órdenes Completadas</p>
                </div>
            </x-ui.card>

            <!-- Metric 3: Average Ticket -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-purple-50 dark:bg-purple-900/30 rounded-xl text-purple-500 dark:text-purple-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold {{ $ticketChange >= 0 ? 'text-green-500 bg-green-50' : 'text-red-500 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $ticketChange >= 0 ? '+' : '' }}{{ number_format($ticketChange, 1) }}%
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatMoney($averageTicket) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ticket Promedio</p>
                </div>
            </x-ui.card>

            <!-- Metric 4: Unique Customers -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-orange-50 dark:bg-orange-900/30 rounded-xl text-orange-500 dark:text-orange-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-xs font-bold {{ $customersChange >= 0 ? 'text-green-500 bg-green-50' : 'text-red-500 bg-red-50' }} px-2 py-1 rounded-full">
                        {{ $customersChange >= 0 ? '+' : '' }}{{ number_format($customersChange, 1) }}%
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($uniqueCustomers) }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Clientes Únicos</p>
                </div>
            </x-ui.card>
        </div>

        <!-- Additional Metrics - Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Metric 5: Cash Revenue -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-500 dark:text-green-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatMoney($cashRevenue) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresos en Efectivo</p>
                </div>
            </x-ui.card>

            <!-- Metric 6: Card Revenue -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div
                        class="p-4 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl text-indigo-500 dark:text-indigo-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatMoney($cardRevenue) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ingresos con Tarjeta</p>
                </div>
            </x-ui.card>

            <!-- Metric 7: Dine-In Orders -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-pink-50 dark:bg-pink-900/30 rounded-xl text-pink-500 dark:text-pink-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($dineInOrders) }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pedidos en Mesa</p>
                </div>
            </x-ui.card>

            <!-- Metric 8: Takeaway + Delivery -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div
                        class="p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-xl text-yellow-500 dark:text-yellow-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($takeawayOrders + $deliveryOrders) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Para Llevar / Delivery</p>
                </div>
            </x-ui.card>
        </div>

        <!-- Operational Metrics - Row 3 (NEW) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Metric 9: Net Profit -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div
                        class="p-4 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-emerald-500 dark:text-emerald-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ formatMoney($netProfit) }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ganancia Neta (Est. 20%)</p>
                </div>
            </x-ui.card>

            <!-- Metric 10: Total Items Sold -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-500 dark:text-blue-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalItemsSold) }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Total Items Vendidos</p>
                </div>
            </x-ui.card>

            <!-- Metric 11: Cancelled Orders -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-4 bg-red-50 dark:bg-red-900/30 rounded-xl text-red-500 dark:text-red-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-red-500 bg-red-50 px-2 py-1 rounded-full">
                        {{ formatMoney($cancelledAmount) }}
                    </span>
                </div>
                <div class="mt-4">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($cancelledOrders) }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Órdenes Canceladas</p>
                </div>
            </x-ui.card>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Revenue Line Chart -->
            <x-ui.card class="p-6 lg:col-span-2">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 dark:text-white">Ingresos por Mes</h3>
                </div>
                <div class="h-80 w-full">
                    <canvas id="incomeChart"></canvas>
                </div>
            </x-ui.card>

            <!-- Top Products Pie Chart -->
            <x-ui.card class="p-6">
                <h3 class="font-bold text-gray-900 dark:text-white mb-6">Platillos Más Vendidos</h3>
                <div class="h-64 w-full flex items-center justify-center">
                    <canvas id="productsChart"></canvas>
                </div>
            </x-ui.card>
        </div>

        <!-- Additional Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Hourly Sales Bar Chart -->
            <x-ui.card class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 dark:text-white">Ventas por Hora del Día</h3>
                </div>
                <div class="h-64 w-full">
                    <canvas id="hourlyChart"></canvas>
                </div>
            </x-ui.card>

            <!-- Payment Methods Pie Chart -->
            <x-ui.card class="p-6">
                <h3 class="font-bold text-gray-900 dark:text-white mb-6">Métodos de Pago</h3>
                <div class="h-64 w-full flex items-center justify-center">
                    <canvas id="paymentChart"></canvas>
                </div>
            </x-ui.card>
        </div>

        <!-- Daily Trends Chart -->
        <x-ui.card class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-900 dark:text-white">Tendencia Diaria (Últimos 30 Días)</h3>
            </div>
            <div class="h-64 w-full">
                <canvas id="dailyTrendsChart"></canvas>
            </div>
        </x-ui.card>

        <!-- Categories Report Table -->
        <x-ui.card class="overflow-hidden">
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-bold text-gray-900 dark:text-white">Rendimiento por Categoría</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead
                        class="bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-300 text-xs uppercase font-semibold">
                        <tr>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4 text-right">Ventas</th>
                            <th class="px-6 py-4 text-right">Ingresos</th>
                            <th class="px-6 py-4 text-right">Margen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($categoryPerformance as $category)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div
                                        class="p-2 bg-orange-50 dark:bg-orange-900/30 text-orange-500 dark:text-orange-400 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $category->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300">
                                    {{ number_format($category->total_sales) }}</td>
                                <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300">
                                    {{ formatMoney($category->total_revenue) }}</td>
                                <td class="px-6 py-4 text-right text-gray-600 dark:text-gray-300">
                                    {{ number_format($category->margin) }}%
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No hay datos de categorías disponibles
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>

    @push('scripts')
        <script>
            // Pass PHP data to JavaScript
            window.reportsData = {
                monthlyRevenue: @json($monthlyRevenue),
                topProducts: @json($topProducts),
                hourlyData: @json($hourlyData),
                paymentMethods: @json($paymentMethods),
                dailyTrends: @json($dailyTrends)
            };

            console.log('Reports Data Loaded:', window.reportsData);
        </script>
    @endpush
</x-app-layout>
