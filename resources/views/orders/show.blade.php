<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mb-2 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    Volver a pedidos
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pedido {{ $order->order_number }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Detalles completos del pedido</p>
            </div>
            @php
                $statusClasses = match ($order->status) {
                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'preparing' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                    'ready' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
                    'in_transit' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400',
                    'delivered' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    default => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                };
            @endphp
            <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $statusClasses }}">
                {{ $order->status_label }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Información del Cliente
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        @if ($order->order_source === 'waiter')
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Mesero</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->waiter->name ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Mesa</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->table ? $order->table->table_number : 'N/A' }}</p>
                            </div>
                        @else
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Nombres y Apellidos
                                </p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->customer_name }}
                                    {{ $order->customer_lastname }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Teléfono</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->customer_phone }}</p>
                            </div>
                            @if ($order->customer_email)
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Email</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $order->customer_email }}
                                    </p>
                                </div>
                            @endif
                        @endif

                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de Pedido</p>
                            <p class="font-semibold text-gray-900 dark:text-white capitalize">{{ $order->order_type }}
                            </p>
                        </div>
                        @if ($order->delivery_address)
                            <div class="col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Dirección de
                                    Entrega</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->delivery_address }}
                                </p>
                            </div>
                        @endif
                        @if ($order->notes)
                            <div class="col-span-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Notas</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $order->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Items del Pedido
                    </h2>
                    <div class="space-y-3">
                        @foreach ($order->items as $item)
                            <div
                                class="flex justify-between items-start p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item->product_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Cantidad: <span
                                            class="font-medium">{{ $item->quantity }}</span></p>
                                    @if ($item->special_instructions)
                                        <div class="mt-2 p-2 bg-blue-50 rounded-lg">
                                            <p class="text-xs text-blue-700">
                                                <span class="font-semibold">Instrucciones:</span>
                                                {{ $item->special_instructions }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-right ml-4">
                                    <p class="font-bold text-gray-900 dark:text-white">
                                        {{ formatMoney($item->subtotal) }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ formatMoney($item->unit_price) }} c/u
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="mt-6 space-y-2 pt-4 border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                            <span>Subtotal:</span>
                            <span class="font-medium">{{ formatMoney($order->subtotal) }}</span>
                        </div>

                        <div
                            class="flex justify-between text-lg font-bold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span>Total:</span>
                            <span>{{ formatMoney($order->total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Update Status -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Actualizar Estado
                    </h2>
                    <form method="POST" action="{{ route('orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Nuevo
                                    Estado</label>
                                <select name="status"
                                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                        Pendiente</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>
                                        Confirmado</option>
                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>
                                        Preparando</option>
                                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Listo
                                    </option>
                                    <option value="in_transit" {{ $order->status == 'in_transit' ? 'selected' : '' }}>
                                        En Camino</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                        Entregado</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                        Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Notas
                                    (opcional)</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="Agregar notas sobre el cambio de estado..."></textarea>
                            </div>
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-colors">
                                Actualizar Estado
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Status History -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Historial de Estados
                    </h2>
                    <div class="space-y-3">
                        @forelse($order->statusHistory as $history)
                            <div class="border-l-2 border-blue-200 pl-4 py-2">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $history->from_status }} →
                                    {{ $history->to_status }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ formatDateTimeFull($history->created_at) }}</p>
                                @if ($history->user)
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Por:
                                        {{ $history->user->name }}</p>
                                @endif
                                @if ($history->notes)
                                    <p
                                        class="text-xs text-gray-600 dark:text-gray-300 mt-1 bg-gray-50 dark:bg-gray-700/50 p-2 rounded">
                                        {{ $history->notes }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No hay historial de
                                cambios</p>
                        @endforelse
                    </div>
                </div>

                <!-- Order Info -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Información del Pedido
                    </h2>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Fecha del Pedido:</span>
                            <span
                                class="font-medium text-gray-900 dark:text-white">{{ formatDateTimeFull($order->order_date) }}</span>
                        </div>
                        @if ($order->confirmed_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Confirmado:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ formatDateTimeFull($order->confirmed_at) }}</span>
                            </div>
                        @endif
                        @if ($order->ready_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Listo:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ formatDateTimeFull($order->ready_at) }}</span>
                            </div>
                        @endif
                        @if ($order->in_transit_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">En Camino:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ formatDateTimeFull($order->in_transit_at) }}</span>
                            </div>
                        @endif
                        @if ($order->delivered_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Entregado:</span>
                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ formatDateTimeFull($order->delivered_at) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
