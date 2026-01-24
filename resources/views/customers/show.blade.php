<x-app-layout>
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('customers.index') }}"
                    class="text-sm text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 mb-2 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Volver a Clientes
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $customer->full_name }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Cliente desde
                    {{ $customer->created_at->format('d/m/Y') }}</p>
            </div>
            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="delete-customer-form">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="h-11 px-5 rounded-xl bg-red-600 text-white text-sm font-bold hover:bg-red-700 flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Eliminar Cliente
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Customer Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Customer Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="text-center mb-6">
                        <div
                            class="w-24 h-24 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center text-4xl font-bold mx-auto mb-4">
                            {{ strtoupper(substr($customer->customer_name, 0, 1)) }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $customer->full_name }}</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</label>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                {{ $customer->customer_email ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <label
                                class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Teléfono</label>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                {{ $customer->customer_phone ?? 'No registrado' }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">DNI</label>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                {{ $customer->customer_dni ?? 'No registrado' }}</p>
                        </div>
                        @if ($customer->delivery_address)
                            <div>
                                <label
                                    class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dirección</label>
                                <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $customer->delivery_address }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistics Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Estadísticas</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total de
                                Pedidos</label>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $customer->total_orders }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total
                                Gastado</label>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">S/
                                {{ number_format($customer->total_spent, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Promedio por
                                Pedido</label>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">S/
                                {{ number_format($customer->average_order_value, 2) }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Último
                                Pedido</label>
                            <p class="text-sm text-gray-900 dark:text-white mt-1">
                                {{ $customer->last_order_date ? $customer->last_order_date->diffForHumans() : 'Nunca' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Orders History -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Completados</p>
                                <p class="text-3xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pendientes</p>
                                <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cancelados</p>
                                <p class="text-3xl font-bold text-red-600">{{ $stats['cancelled_orders'] }}</p>
                            </div>
                            <div
                                class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorite Products -->
                @if ($stats['favorite_products']->count() > 0)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 shadow-sm">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Productos Favoritos</h4>
                        <div class="space-y-3">
                            @foreach ($stats['favorite_products'] as $product)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span
                                            class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</span>
                                    </div>
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        {{ $product->total_quantity }} veces
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Orders Table -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Historial de Pedidos</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead
                                class="bg-gray-50 dark:bg-gray-900 text-xs uppercase text-gray-700 dark:text-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-bold">Nº Pedido</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Fecha</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Tipo</th>
                                    <th scope="col" class="px-6 py-4 font-bold">Estado</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-right">Total</th>
                                    <th scope="col" class="px-6 py-4 font-bold text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4">
                                            <span
                                                class="font-mono text-xs font-bold text-blue-600 dark:text-blue-400">{{ $order->order_number }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($order->order_source == 'online')
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">Web</span>
                                            @elseif($order->order_source == 'waiter')
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">Mesero</span>
                                            @else
                                                <span
                                                    class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">QR</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusClasses = match ($order->status) {
                                                    'delivered'
                                                        => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                    'cancelled'
                                                        => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                                    'ready'
                                                        => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                                    default
                                                        => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                };
                                            @endphp
                                            <span
                                                class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusClasses }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white text-right">
                                            S/ {{ number_format($order->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Ver
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            No hay pedidos registrados
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($orders->hasPages())
                        <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-customer-form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción eliminará el cliente y su historial",
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
        });
    </script>
</x-app-layout>
