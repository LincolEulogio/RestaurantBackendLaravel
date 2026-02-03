<x-app-layout>
    <div x-data="orderManager()" class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Pedidos</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Administra y monitorea todos los pedidos del
                    restaurante</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Orders Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Pedidos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Orders Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $pendingOrders }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- In Progress Orders Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">En Proceso</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $inProgressOrders }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Orders Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Completados</p>
                        <p class="text-3xl font-bold text-green-600">{{ $completedOrders }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Entregados --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Entregados</p>
                        <p class="text-3xl font-bold text-green-600">{{ $deliveredOrders }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Cancelados --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cancelados</p>
                        <p class="text-3xl font-bold text-red-600">{{ $cancelledOrders }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
            <form method="GET" action="{{ route('orders.index') }}" class="flex flex-col lg:flex-row gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        x-on:input.debounce.500ms="$el.form.submit()"
                        placeholder="Buscar por número de orden, cliente o teléfono..."
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                </div>

                <!-- Date -->
                <div class="w-full lg:w-auto">
                    <input type="date" name="date" value="{{ request('date') }}" x-on:change="$el.form.submit()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                </div>

                <!-- Order Type -->
                <div class="w-full lg:w-auto">
                    <select name="order_type" x-on:change="$el.form.submit()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                        <option value="all">Todos los tipos</option>
                        <option value="pickup" {{ request('order_type') == 'pickup' ? 'selected' : '' }}>Pickup
                        </option>
                        <option value="delivery" {{ request('order_type') == 'delivery' ? 'selected' : '' }}>Delivery
                        </option>
                        <option value="dine-in" {{ request('order_type') == 'dine-in' ? 'selected' : '' }}>Dine-in
                        </option>
                    </select>
                </div>

                <!-- Status -->
                <div class="w-full lg:w-auto">
                    <select name="status" x-on:change="$el.form.submit()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                        <option value="all">Todos los estados</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado
                        </option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparando
                        </option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Listo</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-2">
                    <a href="{{ route('orders.index') }}"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-colors inline-flex items-center justify-center whitespace-nowrap">
                        Resetear
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase text-gray-700 dark:text-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">#</th>
                            <th scope="col" class="px-6 py-4 font-bold">Número</th>
                            <th scope="col" class="px-6 py-4 font-bold">Cliente</th>
                            <th scope="col" class="px-6 py-4 font-bold">Tipo</th>
                            <th scope="col" class="px-6 py-4 font-bold">Estado</th>
                            <th scope="col" class="px-6 py-4 font-bold">Total</th>
                            <th scope="col" class="px-6 py-4 font-bold">Fecha</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-sm font-bold text-gray-900 dark:text-white">{{ $order->id }}</span>
                                </td>
                                <td
                                    class="px-6 py-4 font-mono text-xs text-blue-600 bg-blue-50 dark:bg-blue-600 dark:text-white rounded-md inline-block my-3 mx-6">
                                    {{ $order->order_number }}
                                </td>
                                <td class="px-6 py-4">
                                    @if ($order->order_source === 'waiter')
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            Mesa {{ $order->table ? $order->table->table_number : 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Mesero: {{ $order->waiter->name ?? 'N/A' }}
                                        </div>
                                    @else
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $order->customer_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $order->customer_phone }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-gray-900 dark:text-white capitalize">{{ $order->order_type }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = match ($order->status) {
                                            'pending'
                                                => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                            'confirmed'
                                                => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'preparing'
                                                => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'ready'
                                                => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-900/30 dark:text-cyan-400',
                                            'in_transit'
                                                => 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400',
                                            'delivered'
                                                => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            default => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusClasses }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
                                    {{ formatMoney($order->total) }}
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ formatDateTimeFull($order->order_date) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Ver detalles
                                        </a>

                                        <!-- Delete Button -->
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                            class="inline delete-order-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:hover:text-red-400 rounded-lg transition-colors"
                                                title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No se encontraron pedidos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Professional Pagination -->
        @if ($orders->hasPages())
            <div>
                <div class="flex justify-center py-4">
                    <nav class="flex items-center gap-2" aria-label="Pagination">
                        <!-- Previous Button -->
                        @if ($orders->onFirstPage())
                            <button disabled
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 opacity-50 cursor-not-allowed transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}"
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 hover:text-white hover:border-gray-600 transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        <div class="flex items-center gap-2">
                            @foreach (range(1, $orders->lastPage()) as $page)
                                @if ($page == $orders->currentPage())
                                    <button
                                        class="w-10 h-10 rounded-lg text-sm font-bold bg-blue-600 text-white shadow-lg shadow-blue-500/30 border-0 flex items-center justify-center">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $orders->url($page) }}"
                                        class="w-10 h-10 rounded-lg text-sm font-bold bg-gray-800 text-gray-400 border border-gray-700 hover:text-white hover:border-gray-600 transition-all flex items-center justify-center">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Next Button -->
                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}"
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 hover:text-white hover:border-gray-600 transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <button disabled
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 opacity-50 cursor-not-allowed transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endif
                    </nav>
                </div>
            </div>
        @endif

    </div>

    <script>
        function orderManager() {
            return {
                init() {
                    console.log('Order Manager initialized');
                    this.setupDeleteConfirmation();
                },
                setupDeleteConfirmation() {
                    // Add event listeners to all delete forms
                    document.querySelectorAll('.delete-order-form').forEach(form => {
                        form.addEventListener('submit', (e) => {
                            e.preventDefault();

                            Swal.fire({
                                title: '¿Estás seguro?',
                                text: "No podrás revertir esta acción",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sí, eliminar',
                                cancelButtonText: 'Cancelar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        });
                    });
                }
            }
        }
    </script>
</x-app-layout>
