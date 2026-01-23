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

                    // Auto-refresh using AJAX every 3 seconds
                    setInterval(async () => {
                        try {
                            const response = await fetch('{{ route('kitchen.fetch-orders') }}');
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
