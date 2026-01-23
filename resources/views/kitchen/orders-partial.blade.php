@forelse($orders as $order)
    <!-- Order Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl border-l-4 
        @if ($order->status == 'pending') border-orange-500
        @elseif($order->status == 'confirmed') border-blue-500
        @elseif($order->status == 'preparing') border-blue-600
        @elseif($order->status == 'ready') border-green-500 @endif
        shadow-sm overflow-hidden flex flex-col">

        <!-- Order Header -->
        <div
            class="p-4 border-b dark:border-gray-700
            @if ($order->status == 'pending') bg-orange-50 dark:bg-orange-900/20 border-orange-100
            @elseif($order->status == 'confirmed') bg-blue-50 dark:bg-blue-900/20 border-blue-100
            @elseif($order->status == 'preparing') bg-blue-50 dark:bg-blue-900/20 border-blue-100
            @elseif($order->status == 'ready') bg-green-50 dark:bg-green-900/20 border-green-100 @endif
            flex justify-between items-center">
            <div>
                <span
                    class="text-xs font-bold uppercase dark:text-gray-100
                    @if ($order->status == 'pending') text-orange-700 dark:text-orange-100
                    @elseif($order->status == 'confirmed') text-blue-700 dark:text-blue-100
                    @elseif($order->status == 'preparing') text-blue-700 dark:text-blue-100
                    @elseif($order->status == 'ready') text-green-700 dark:text-green-100 @endif">
                    @if ($order->table)
                        Mesa {{ $order->table->table_number }}
                        <span class="block text-[10px] opacity-75">
                            {{ $order->order_source == 'waiter' ? '🛎️ Mesero' : '📱 QR' }}
                        </span>
                    @else
                        {{ ucfirst($order->order_type ?? 'Web') }}
                    @endif
                </span>
                <p class="text-sm font-bold text-gray-900 dark:text-gray-100">{{ $order->order_number }}</p>
            </div>
            <div class="text-right">
                <span
                    class="text-xs font-mono text-gray-500 bg-white border border-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 px-2 py-1 rounded-lg block">
                    {{ formatTime($order->created_at, true) }}
                </span>
                <span class="text-xs text-gray-400 mt-1 block dark:text-gray-100">
                    {{ $order->created_at->diffForHumans() }}
                </span>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-4 flex-1 space-y-3">
            @foreach ($order->items as $item)
                <div class="flex items-start gap-3">
                    <span
                        class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold px-2 py-0.5 rounded min-w-[2rem] text-center">
                        {{ $item->quantity }}
                    </span>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                            {{ $item->product->name ?? $item->product_name }}</p>
                        @if ($item->special_instructions)
                            <span
                                class="text-xs text-red-500 italic block mt-1">{{ $item->special_instructions }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Customer Info -->
        @if ($order->customer_name)
            <div class="px-4 py-2 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                <p class="text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Cliente:</span>
                    {{ $order->customer_name }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Apellidos:</span>
                    {{ $order->customer_lastname }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Telefono:</span>
                    {{ $order->customer_phone }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Correo:</span>
                    {{ $order->customer_email }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-300">
                    <span class="font-semibold text-gray-700 dark:text-gray-200">Direccion:</span>
                    {{ $order->delivery_address }}
                </p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="p-3 bg-gray-50 dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
            @if ($order->status == 'pending')
                <form action="{{ route('kitchen.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-xl transition-all dark:bg-orange-500 dark:hover:bg-orange-600">
                        Empezar Preparación
                    </button>
                </form>
            @elseif($order->status == 'confirmed' || $order->status == 'preparing')
                <form action="{{ route('kitchen.update-status', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="ready">
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-xl transition-all dark:bg-green-500 dark:hover:bg-green-600">
                        Marcar Listo
                    </button>
                </form>
            @elseif($order->status == 'ready')
                <div class="text-center">
                    <span class="inline-flex items-center gap-2 text-green-600 font-bold text-sm dark:text-green-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Listo para Entregar
                    </span>
                </div>
            @endif
        </div>
    </div>
@empty
    <!-- Empty State -->
    <div class="col-span-full">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-400 mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hay pedidos activos</h3>
            <p class="text-gray-500 dark:text-gray-400">Los nuevos pedidos aparecerán aquí automáticamente
            </p>
        </div>
    </div>
@endforelse

<!-- Data required for counters -->
<div id="kitchen-counts-data" data-pending="{{ $pendingCount }}" data-preparing="{{ $preparingCount }}"
    data-ready="{{ $readyCount }}" style="display:none;"></div>
