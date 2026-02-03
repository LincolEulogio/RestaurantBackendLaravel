<!-- Kitchen Dashboard - Preparation Queue -->

<!-- Metrics Overview -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Pending Orders -->
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
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Por iniciar</p>
        </div>
    </div>

    <!-- Preparing Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z">
                    </path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">En Preparación</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $preparingOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En cocina</p>
        </div>
    </div>

    <!-- Ready Orders -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-cyan-50 rounded-lg text-cyan-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Listos</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $readyOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Para entregar</p>
        </div>
    </div>

    <!-- Completed Today -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Completados Hoy</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $completedToday }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Órdenes entregadas</p>
        </div>
    </div>
</div>

<!-- Active Orders Queue -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100">Cola de Pedidos Activos</h3>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $activeOrders->count() }} pedidos</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr
                    class="bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider">
                    <th class="px-6 py-3 font-semibold">Pedido</th>
                    <th class="px-6 py-3 font-semibold">Tipo</th>
                    <th class="px-6 py-3 font-semibold">Cliente</th>
                    <th class="px-6 py-3 font-semibold">Estado</th>
                    <th class="px-6 py-3 font-semibold">Hora</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($activeOrders as $order)
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
                                    'ready' => 'bg-cyan-100 text-cyan-800',
                                ];
                                $statusLabels = [
                                    'pending' => 'Pendiente',
                                    'confirmed' => 'Confirmado',
                                    'preparing' => 'Preparando',
                                    'ready' => 'Listo',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 dark:text-gray-100' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->created_at->format('g:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            No hay pedidos activos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
