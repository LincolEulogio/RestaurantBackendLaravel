<x-app-layout>
    <div x-data="kitchenManager()" class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-gray-100 tracking-tight">KDS - Cocina</h1>
                <p class="text-gray-500">Sistema de visualización de pedidos para cocina</p>
            </div>
            <div class="flex gap-3">
                <div
                    class="bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-orange-500 animate-pulse"></span>
                    <span id="count-pending" class="font-bold text-gray-700 dark:text-gray-100">{{ $pendingCount }}
                        Pendientes</span>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <span id="count-preparing" class="font-bold text-gray-700 dark:text-gray-100">{{ $preparingCount }}
                        Preparando</span>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span>
                    <span id="count-ready" class="font-bold text-gray-700 dark:text-gray-100">{{ $readyCount }}
                        Listos</span>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
            <form method="GET" action="{{ route('kitchen.index') }}" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Estado
                    </label>
                    <select name="status"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        onchange="this.form.submit()">
                        <option value="all"
                            {{ request('status') === 'all' || !request('status') ? 'selected' : '' }}>Todos los estados
                        </option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmado
                        </option>
                        <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>Preparando
                        </option>
                        <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Listo</option>
                    </select>
                </div>

                <!-- Source Filter -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Origen
                    </label>
                    <select name="source"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        onchange="this.form.submit()">
                        <option value="all"
                            {{ request('source') === 'all' || !request('source') ? 'selected' : '' }}>Todos los orígenes
                        </option>
                        <option value="waiter" {{ request('source') === 'waiter' ? 'selected' : '' }}>Mesero
                        </option>
                        <option value="web" {{ request('source') === 'web' ? 'selected' : '' }}>Web</option>
                    </select>
                </div>

                <!-- Time Range Filter -->
                <div>
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Período
                    </label>
                    <select name="time_range"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                        onchange="this.form.submit()">
                        <option value="" {{ !request('time_range') ? 'selected' : '' }}>Todo el tiempo</option>
                        <option value="last_hour" {{ request('time_range') === 'last_hour' ? 'selected' : '' }}>
                            Última hora</option>
                        <option value="last_3_hours" {{ request('time_range') === 'last_3_hours' ? 'selected' : '' }}>
                            Últimas 3 horas</option>
                        <option value="today" {{ request('time_range') === 'today' ? 'selected' : '' }}>Hoy
                        </option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="flex items-end">
                    <a href="{{ route('kitchen.index') }}"
                        class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-center font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Limpiar Filtros
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders Grid -->
        <div x-ref="ordersContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @include('kitchen.orders-partial', [
                'orders' => $orders,
                'pendingCount' => $pendingCount,
                'preparingCount' => $preparingCount,
                'readyCount' => $readyCount,
            ])
        </div>
    </div>

    <script>
        function kitchenManager() {
            return {
                init() {
                    console.log('Kitchen Manager (Real-time) initialized');

                    // Auto-refresh using AJAX every 3 seconds with current filters
                    setInterval(async () => {
                        try {
                            const params = new URLSearchParams(window.location.search);
                            const response = await fetch('{{ route('kitchen.fetch-orders') }}?' + params
                                .toString());
                            if (response.ok) {
                                const html = await response.text();

                                // Parse the HTML to extract data and content
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');

                                // Update orders grid
                                this.$refs.ordersContainer.innerHTML = doc.body.innerHTML;

                                // Update counters from hidden data div in partial
                                const countsData = doc.getElementById('kitchen-counts-data');
                                if (countsData) {
                                    document.getElementById('count-pending').textContent = countsData.dataset
                                        .pending + ' Pendientes';
                                    document.getElementById('count-preparing').textContent = countsData.dataset
                                        .preparing + ' Preparando';
                                    document.getElementById('count-ready').textContent = countsData.dataset
                                        .ready + ' Listos';
                                }
                            }
                        } catch (error) {
                            console.error('Failed to fetch kitchen orders:', error);
                        }
                    }, 3000);
                }
            }
        }
    </script>
</x-app-layout>
