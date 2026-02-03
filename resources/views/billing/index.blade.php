<x-app-layout>
    <div x-data='billingManager(@json($readyOrders))' class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight dark:text-white">Caja y Facturación</h1>
                <p class="text-gray-500 dark:text-gray-400">Gestión de cobros y cierre de caja</p>
            </div>

            <!-- Quick Stats (Optional) -->
            <div class="flex gap-4">
                <div
                    class="bg-white dark:bg-gray-800 px-4 py-2 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <p class="text-xs text-gray-500 uppercase font-bold">Por Cobrar</p>
                    <p class="text-xl font-black text-orange-500">{{ formatMoney($pendingPayments ?? 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Professional Filter Section --}}
        <div x-data="filterManager()"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-6">
            <form id="filterForm" method="GET" action="{{ route('billing.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
                    {{-- Date Filter Preset --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Período Rápido
                        </label>
                        <select name="date_filter" x-model="dateFilter" @change="autoSubmit()"
                            class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-slate-800 focus:border-transparent">
                            <option value="today">Hoy</option>
                            <option value="yesterday">Ayer</option>
                            <option value="this_week">Esta Semana</option>
                            <option value="this_month">Este Mes</option>
                            <option value="custom">Personalizado</option>
                        </select>
                    </div>

                    {{-- Custom Date From (Always Visible) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Desde
                        </label>
                        <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" x-model="dateFrom"
                            @change="handleDateChange()"
                            class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-slate-800 focus:border-transparent">
                    </div>

                    {{-- Custom Date To (Always Visible) --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Hasta
                        </label>
                        <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" x-model="dateTo"
                            @change="handleDateChange()"
                            class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-slate-800 focus:border-transparent">
                    </div>

                    {{-- Order Type Filter --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Tipo de Pedido
                        </label>
                        <select name="order_type_filter" x-model="orderTypeFilter" @change="autoSubmit()"
                            class="w-full px-4 py-2.5 rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-semibold focus:ring-2 focus:ring-slate-800 focus:border-transparent">
                            <option value="all">Todos</option>
                            <option value="delivery">Delivery</option>
                            <option value="waiter">Mesero</option>
                        </select>
                    </div>

                    {{-- Clear Button Only --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Limpiar Filtros
                        </label>
                        <a href="{{ route('billing.index') }}"
                            class="bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-6 py-2 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Limpiar Filtros
                        </a>
                    </div>
                </div>



                {{-- Active Filters Display --}}
                @if ($dateFilter !== 'today' || $orderTypeFilter !== 'all')
                    <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs font-bold text-gray-400 uppercase">Filtros activos:</span>

                        @if ($dateFilter !== 'today')
                            <span
                                class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 px-3 py-1 rounded-full text-xs font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                @if ($dateFilter === 'yesterday')
                                    Ayer
                                @elseif($dateFilter === 'this_week')
                                    Esta Semana
                                @elseif($dateFilter === 'this_month')
                                    Este Mes
                                @elseif($dateFilter === 'custom')
                                    {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : '' }}
                                    -
                                    {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : '' }}
                                @endif
                            </span>
                        @endif

                        @if ($orderTypeFilter !== 'all')
                            <span
                                class="inline-flex items-center gap-1.5 bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-3 py-1 rounded-full text-xs font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                {{ $orderTypeFilter === 'delivery' ? 'Delivery' : 'Mesero' }}
                            </span>
                        @endif
                    </div>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order List -->
            <div class="lg:col-span-2 space-y-4">
                <h3 class="font-bold text-gray-800 text-lg dark:text-white flex items-center justify-between">
                    <span>Pedidos Listos</span>
                    <span
                        class="text-xs font-normal bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-500">{{ $readyOrders->count() }}
                        pedidos</span>
                </h3>

                @forelse($readyOrders as $order)
                    <div @click="selectOrder({{ $order->id }}, '{{ $order->payment_status }}')"
                        :class="selectedOrderId === {{ $order->id }} ?
                            'border-slate-800 ring-1 ring-slate-800 bg-slate-50/50 dark:bg-slate-900/20' :
                            'border-gray-200 dark:border-gray-700 hover:border-slate-800 dark:hover:border-slate-800'"
                        class="bg-white dark:bg-gray-800 rounded-2xl border-2 shadow-sm p-4 transition-all cursor-pointer relative overflow-hidden group">

                        <!-- Status Strip -->
                        <div
                            class="absolute left-0 top-0 bottom-0 w-1.5 
                            {{ $order->payment_status === 'paid' ? 'bg-green-500' : 'bg-orange-500' }}">
                        </div>

                        <div class="pl-3">
                            <!-- Top Row: ID, Source, Time, Status -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-black text-lg text-gray-900 dark:text-white">
                                            {{ $order->order_number }}
                                        </span>
                                        @if ($order->order_source === 'waiter')
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300">
                                                Mesero
                                            </span>
                                        @elseif($order->order_source === 'web' || $order->order_source === 'online')
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                                                Web
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $order->order_source }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $order->created_at->format('h:i A') }}
                                        <span class="mx-1">•</span>
                                        Hace {{ $order->created_at->diffForHumans(null, true) }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <span class="block text-xl font-black text-gray-900 dark:text-white">
                                        {{ formatMoney($order->total) }}
                                    </span>
                                    @if ($order->payment_status === 'paid')
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            PAGADO
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 text-xs font-bold text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30 px-2 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            POR COBRAR
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Middle Row: Customer / Location info -->
                            <div
                                class="grid grid-cols-2 gap-4 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Ubicación</p>
                                    @if ($order->table_number)
                                        <p
                                            class="font-bold text-gray-800 dark:text-gray-200 text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 100-4 2 2 0 000 4z">
                                                </path>
                                            </svg>
                                            Mesa {{ $order->table_number }}
                                        </p>
                                    @elseif($order->order_type === 'delivery')
                                        <p class="font-bold text-gray-800 dark:text-gray-200 text-sm flex items-center gap-1 truncate"
                                            title="{{ $order->delivery_address }}">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Delivery
                                        </p>
                                    @else
                                        <p
                                            class="font-bold text-gray-800 dark:text-gray-200 text-sm flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                            </svg>
                                            Para llevar
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Cliente</p>
                                    <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate">
                                        {{ $order->customer_name ?: 'Invitado' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Bottom Row: Items Summary & Payment Info -->
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex-1 mr-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($order->items->take(3) as $item)
                                            <span
                                                class="bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded text-gray-600 dark:text-gray-300">
                                                <b>{{ $item->quantity }}x</b>
                                                {{ $item->product->name ?? $item->product_name }}
                                            </span>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                            <span class="text-gray-400 pl-1">+{{ $order->items->count() - 3 }}
                                                más</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($order->payment_status === 'paid')
                                    <div class="text-right">
                                        <span class="text-gray-400 text-[10px] uppercase block">Pagado con</span>
                                        <span class="font-bold text-gray-700 dark:text-gray-300 capitalize">
                                            {{ $order->payment_method === 'card' ? 'Tarjeta' : ($order->payment_method ?: 'Online') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-12 text-center">
                        <div
                            class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hay pedidos</h3>
                        <p class="text-gray-500 dark:text-gray-400">No hay pedidos pendientes ni listos en este
                            momento.</p>
                    </div>
                @endforelse
            </div>

            <!-- Right Panel: Payment Interface -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xl flex flex-col h-[calc(100vh-100px)] sticky top-6">

                <!-- Empty State -->
                <template x-if="!selectedOrderId">
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                        <div
                            class="w-20 h-20 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center mb-6 animate-pulse">
                            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Selecciona un Pedido</h3>
                        <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Haz clic en un pedido de la lista
                            para ver su detalle y procesar el cobro.</p>
                    </div>
                </template>

                <!-- Order Details -->
                <template x-if="selectedOrderId">
                    <div class="flex flex-col h-full">
                        <!-- Header -->
                        <div
                            class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 rounded-t-2xl">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-bold text-xl text-gray-900 dark:text-white">Detalle de Cobro</h3>
                                <template x-if="selectedOrderPaymentStatus === 'paid'">
                                    <span
                                        class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border border-green-200">
                                        CONFIRMADO
                                    </span>
                                </template>
                            </div>
                            <p class="text-sm text-gray-500 font-mono"
                                x-text="'#' + (selectedOrder ? selectedOrder.order_number : '')"></p>
                        </div>

                        <!-- Content Scroll -->
                        <div class="flex-1 overflow-y-auto p-6 space-y-6">

                            <!-- If Paid: Success Message -->
                            <template x-if="selectedOrderPaymentStatus === 'paid'">
                                <div
                                    class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-xl p-6 text-center">
                                    <div
                                        class="mx-auto w-12 h-12 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <h4 class="font-bold text-green-800 dark:text-green-300 text-lg mb-1">Pedido ya
                                        pagado</h4>
                                    <p class="text-green-600 dark:text-green-400 text-sm mb-4">La transacción ha sido
                                        completada exitosamente.</p>
                                    <div
                                        class="inline-block bg-white dark:bg-gray-800 border-2 dashed border-green-300 dark:border-green-700 rounded-lg px-4 py-2">
                                        <span class="text-xs text-gray-400 uppercase font-bold block mb-0.5">Método de
                                            Pago</span>
                                        <span class="font-bold text-gray-800 dark:text-white text-lg capitalize"
                                            x-text="selectedOrder ? (selectedOrder.payment_method === 'card' ? 'Tarjeta' : selectedOrder.payment_method) : 'Desconocido'"></span>
                                    </div>
                                </div>
                            </template>

                            <!-- Items List (Always visible logic) -->
                            <!-- Note: We need the full order object in Alpine to show items.
                                 Refactoring slightly to pass full order object to selectOrder would be ideal.
                                 For now, we rely on the loop data or fetched data.
                                 Better approach: The blade view has the loop. We can pass the whole order object into `selectOrder`.
                            -->
                            <div>
                                <!-- We need to make sure 'selectedOrder' object is available.
                                     I will update the Alpine component below to accept the full object. -->
                                <template x-if="selectedOrder">
                                    <div class="space-y-4">
                                        <template x-for="item in selectedOrder.items" :key="item.id">
                                            <div
                                                class="flex justify-between text-sm py-2 border-b border-dashed border-gray-100 dark:border-gray-700 last:border-0">
                                                <div class="flex gap-3">
                                                    <span class="font-bold text-gray-900 dark:text-white tabular-nums"
                                                        x-text="item.quantity + 'x'"></span>
                                                    <div class="flex-1">
                                                        <p class="text-gray-800 dark:text-gray-200 font-medium"
                                                            x-text="item.product ? item.product.name : item.product_name">
                                                        </p>
                                                        <template x-if="item.notes">
                                                            <p class="text-xs text-gray-400 italic"
                                                                x-text="item.notes"></p>
                                                        </template>
                                                    </div>
                                                </div>
                                                <span class="font-semibold text-gray-900 dark:text-white tabular-nums"
                                                    x-text="formatMoney(item.subtotal)"></span>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                        </div>

                        <!-- Footer: Actions / Payment -->
                        <div
                            class="p-6 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] z-10">

                            <!-- Total Row -->
                            <div class="flex justify-between items-end mb-6">
                                <span class="text-gray-500 dark:text-gray-400 font-bold">Total Final</span>
                                <span class="text-4xl font-black text-gray-900 dark:text-white tabular-nums"
                                    x-text="formatMoney(currentTotal)"></span>
                            </div>

                            <!-- If Pending: Show Payment Form -->
                            <template x-if="selectedOrderPaymentStatus !== 'paid'">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Seleccionar
                                        Método</label>
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <button @click="paymentMethod = 'cash'"
                                            :class="paymentMethod === 'cash' ?
                                                'bg-green-600 text-white shadow-lg shadow-green-200 ring-2 ring-green-600 ring-offset-2' :
                                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                            class="flex flex-col items-center justify-center p-3 rounded-xl transition-all font-bold">
                                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            Efectivo
                                        </button>
                                        <button @click="openCardModal()"
                                            :class="['card', 'yape', 'plin'].includes(paymentMethod) ?
                                                'bg-blue-600 text-white shadow-lg shadow-blue-200 ring-2 ring-blue-600 ring-offset-2' :
                                                'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                            class="flex flex-col items-center justify-center p-3 rounded-xl transition-all font-bold">
                                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                </path>
                                            </svg>
                                            Digital
                                        </button>
                                    </div>

                                    <!-- Cash Inputs -->
                                    <div x-show="paymentMethod === 'cash'" x-transition
                                        class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl mb-4 border border-gray-100 dark:border-gray-600">
                                        <div class="flex justify-between mb-2">
                                            <label
                                                class="text-sm font-bold text-gray-700 dark:text-gray-300">Recibido</label>
                                            <div x-show="change > 0" class="text-green-600 font-bold text-sm">Vuelto:
                                                <span x-text="formatMoney(change)"></span>
                                            </div>
                                        </div>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">S/</span>
                                            <input type="number" x-model.number="amountReceived" step="0.10"
                                                class="w-full pl-8 pr-4 py-2 rounded-lg border-gray-200 font-bold text-gray-900 bg-white"
                                                placeholder="0.00">
                                        </div>
                                    </div>

                                    <!-- Digital Selection Info -->
                                    <div x-show="['yape', 'plin', 'card'].includes(paymentMethod)"
                                        class="bg-blue-50 text-blue-800 p-3 rounded-xl mb-4 text-center text-sm font-bold border border-blue-100">
                                        <span
                                            x-text="paymentMethod === 'card' ? 'Tarjeta Seleccionada' : paymentMethod.toUpperCase() + ' Seleccionado'"></span>
                                    </div>

                                    <form :action="`/billing/${selectedOrderId}/payment`" method="POST">
                                        @csrf
                                        <input type="hidden" name="payment_method" :value="paymentMethod">
                                        <input type="hidden" name="amount_received"
                                            :value="paymentMethod === 'cash' ? amountReceived : currentTotal">
                                        <button type="submit" :disabled="!canSubmit"
                                            :class="canSubmit ? 'bg-gray-900 hover:bg-black text-white' :
                                                'bg-gray-300 text-gray-500 cursor-not-allowed'"
                                            class="w-full py-4 rounded-xl font-bold text-lg shadow-xl transition-all">
                                            Cobrar
                                        </button>
                                    </form>
                                </div>
                            </template>

                            <!-- If Paid: Print Receipt Button -->
                            <template x-if="selectedOrderPaymentStatus === 'paid'">
                                <button type="button"
                                    class="w-full bg-gray-900 hover:bg-black text-white py-4 rounded-xl font-bold text-lg shadow-xl transition-all flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Imprimir Comprobante
                                </button>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Digital Payment Modal (Unchanged logic, just ensure existing) -->
        <div x-show="showCardModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-transition>
            <!-- ... Modal content matches previous design ... -->
            <div @click.away="showCardModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl transform transition-all p-8 text-center">
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-6">Elige Medio de Pago</h3>
                <div class="grid grid-cols-2 gap-4">
                    <button @click="selectDigitalPayment('yape')"
                        class="group p-6 rounded-2xl border-2 border-purple-100 hover:border-purple-500 hover:bg-purple-50 transition-all flex flex-col items-center gap-3">
                        <div
                            class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M9 8h.01M15 8h.01M9 5h.01M15 5h.01">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-700 group-hover:text-purple-700 text-lg">YAPE</span>
                    </button>
                    <button @click="selectDigitalPayment('plin')"
                        class="group p-6 rounded-2xl border-2 border-cyan-100 hover:border-cyan-500 hover:bg-cyan-50 transition-all flex flex-col items-center gap-3">
                        <div
                            class="w-16 h-16 bg-cyan-100 rounded-2xl flex items-center justify-center text-cyan-600 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M9 8h.01M15 8h.01M9 5h.01M15 5h.01">
                                </path>
                            </svg>
                        </div>
                        <span class="font-bold text-gray-700 group-hover:text-cyan-700 text-lg">PLIN</span>
                    </button>
                    <button @click="selectDigitalPayment('card')"
                        class="col-span-2 group p-6 rounded-2xl border-2 border-blue-100 hover:border-blue-500 hover:bg-blue-50 transition-all flex items-center justify-center gap-4">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span
                                class="block font-bold text-gray-700 group-hover:text-blue-700 text-lg">Tarjeta</span>
                            <span class="text-xs text-gray-400">Visa, Mastercard</span>
                        </div>
                    </button>
                </div>
                <div class="mt-8">
                    <button @click="showCardModal = false"
                        class="text-gray-400 hover:text-gray-600 font-bold">Cancelar</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            // Filter Manager Component
            Alpine.data('filterManager', () => ({
                dateFilter: '{{ $dateFilter ?? 'today' }}',
                orderTypeFilter: '{{ $orderTypeFilter ?? 'all' }}',
                dateFrom: '{{ $dateFrom ?? '' }}',
                dateTo: '{{ $dateTo ?? '' }}',

                autoSubmit() {
                    // Auto-submit the form when any filter changes
                    this.$nextTick(() => {
                        document.getElementById('filterForm').submit();
                    });
                },

                handleDateChange() {
                    // When custom dates are changed, set filter to 'custom' and submit
                    this.dateFilter = 'custom';
                    this.autoSubmit();
                },

                toggleCustomDates() {
                    // Legacy method - no longer needed but kept for compatibility
                }
            }));

            // Billing Manager Component
            Alpine.data('billingManager', (ordersData = []) => ({
                orders: ordersData, // Full orders objects
                selectedOrderId: null,
                selectedOrder: null, // Holds the full order object
                currentTotal: 0,
                selectedOrderPaymentStatus: '',
                paymentMethod: '',
                amountReceived: '',
                showCardModal: false,

                selectOrder(id, status) {
                    this.selectedOrderId = id;
                    // Find the full order object from the initial data
                    // Note: In a real app we might fetch via API, but here we passed the collection.
                    // However, Blade passed readyOrders as JSON.
                    this.selectedOrder = this.orders.find(o => o.id === id);

                    this.currentTotal = parseFloat(this.selectedOrder.total);
                    this.selectedOrderPaymentStatus = status;

                    this.paymentMethod = '';
                    this.amountReceived = '';
                    this.showCardModal = false;
                },

                setPaymentMethod(method) {
                    this.paymentMethod = method;
                },

                openCardModal() {
                    this.showCardModal = true;
                },

                selectDigitalPayment(subMethod) {
                    this.paymentMethod = subMethod;
                    this.showCardModal = false;
                },

                get change() {
                    if (this.paymentMethod !== 'cash') return 0;
                    const received = parseFloat(this.amountReceived) || 0;
                    return Math.max(0, received - this.currentTotal);
                },

                get canSubmit() {
                    if (!this.selectedOrderId) return false;
                    if (!this.paymentMethod) return false;
                    if (this.paymentMethod === 'cash') {
                        const received = parseFloat(this.amountReceived) || 0;
                        return received >= this.currentTotal;
                    }
                    return true;
                },

                formatMoney(amount) {
                    return 'S/ ' + parseFloat(amount).toFixed(2);
                }
            }))
        });
    </script>
</x-app-layout>
