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
                                                class="px-2 py-0.5 rounded text-[10px] font-black uppercase bg-blue-600 text-white shadow-sm ring-1 ring-blue-400">
                                                WEB
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $order->order_source }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                            class="inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full 
                                            {{ ($order->order_source === 'web' || $order->order_source === 'online') ? 'text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30' : 'text-orange-600 dark:text-orange-400 bg-orange-50 dark:bg-orange-900/30' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ ($order->order_source === 'web' || $order->order_source === 'online') ? 'POR VERIFICAR' : 'POR COBRAR' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Middle Row: Customer / Location info -->
                            <div
                                class="grid grid-cols-2 gap-4 mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-100 dark:border-gray-700">
                                <div>
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Tipo de pedido</p>
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
                                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-0.5">Nombres</p>
                                    <p class="font-bold text-gray-800 dark:text-gray-200 text-sm truncate">
                                        {{ $order->customer_name ?: 'Invitado' }}
                                        {{ $order->customer_lastname ?: '' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Additional Customer Info (For Web Orders) --}}
                            @if ($order->order_source === 'web' || $order->order_source === 'online')
                                <div
                                    class="mb-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                                    <div class="grid grid-cols-2 gap-3 text-xs">
                                        {{-- Customer Contact Info --}}
                                        @if ($order->customer_phone)
                                            <div>
                                                <p
                                                    class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 mb-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                        </path>
                                                    </svg>
                                                    Teléfono
                                                </p>
                                                <p class="font-semibold text-gray-800 dark:text-gray-200">
                                                    {{ $order->customer_phone }}</p>
                                            </div>
                                        @endif

                                        @if ($order->customer_email)
                                            <div>
                                                <p
                                                    class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 mb-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                    Email
                                                </p>
                                                <p class="font-semibold text-gray-800 dark:text-gray-200 truncate"
                                                    title="{{ $order->customer_email }}">
                                                    {{ $order->customer_email }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- Delivery Address (if applicable) --}}
                                        @if ($order->delivery_address)
                                            <div class="col-span-2">
                                                <p
                                                    class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 mb-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                    </svg>
                                                    Dirección de Entrega
                                                </p>
                                                <p class="font-semibold text-gray-800 dark:text-gray-200 text-xs">
                                                    {{ $order->delivery_address }}
                                                </p>
                                            </div>
                                        @endif

                                        {{-- Payment Method (Web Orders - if not paid yet) --}}
                                        @if ($order->payment_status !== 'paid' && $order->payment_method)
                                            <div class="col-span-2 pt-2 border-t border-blue-200 dark:border-blue-700">
                                                <p
                                                    class="text-[10px] uppercase font-bold text-blue-600 dark:text-blue-400 mb-0.5 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    </svg>
                                                    Método de Pago Elegido
                                                </p>
                                                <p
                                                    class="font-bold text-gray-900 dark:text-white capitalize flex items-center gap-2">
                                                    @if ($order->payment_method === 'card')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <rect x="1" y="4" width="22" height="16"
                                                                rx="2" ry="2"></rect>
                                                            <line x1="1" y1="10" x2="23"
                                                                y2="10"></line>
                                                        </svg>
                                                        Tarjeta
                                                    @elseif($order->payment_method === 'yape')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <rect x="5" y="2" width="14" height="20"
                                                                rx="2" ry="2"></rect>
                                                            <line x1="12" y1="18" x2="12.01"
                                                                y2="18"></line>
                                                        </svg>
                                                        Yape
                                                    @elseif($order->payment_method === 'plin')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <rect x="5" y="2" width="14" height="20"
                                                                rx="2" ry="2"></rect>
                                                            <line x1="12" y1="18" x2="12.01"
                                                                y2="18"></line>
                                                        </svg>
                                                        Plin
                                                    @elseif($order->payment_method === 'online')
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24" stroke-width="2">
                                                            <circle cx="12" cy="12" r="10"></circle>
                                                            <line x1="2" y1="12" x2="22"
                                                                y2="12"></line>
                                                            <path
                                                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                                            </path>
                                                        </svg>
                                                        Pago Online
                                                    @else
                                                        {{ $order->payment_method }}
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

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
                                            @if ($order->payment_method === 'card')
                                                💳 Tarjeta
                                            @elseif($order->payment_method === 'yape')
                                                📱 Yape
                                            @elseif($order->payment_method === 'plin')
                                                📱 Plin
                                            @elseif($order->payment_method === 'cash')
                                                💵 Efectivo
                                            @else
                                                {{ $order->payment_method ?: 'Online' }}
                                            @endif
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

                            <!-- Billing Data Display -->
                            <template x-if="selectedOrder && selectedOrder.billing_type">
                                <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-100 dark:border-gray-700 rounded-xl p-4 space-y-3">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Datos de Facturación
                                    </h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <p class="text-[10px] text-gray-400 uppercase font-black">Tipo</p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200 capitalize" x-text="selectedOrder.billing_type"></p>
                                        </div>
                                        <template x-if="selectedOrder.billing_type === 'factura'">
                                            <div>
                                                <p class="text-[10px] text-gray-400 uppercase font-black">RUC</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200" x-text="selectedOrder.ruc"></p>
                                            </div>
                                        </template>
                                        <template x-if="selectedOrder.billing_type === 'factura'">
                                        <div class="col-span-2">
                                            <p class="text-[10px] text-gray-400 uppercase font-black">Razón Social</p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200" x-text="selectedOrder.business_name"></p>
                                        </div>
                                        </template>
                                        <template x-if="selectedOrder.billing_type === 'factura'">
                                        <div class="col-span-2">
                                            <p class="text-[10px] text-gray-400 uppercase font-black">Dirección Fiscal</p>
                                            <p class="font-bold text-gray-800 dark:text-gray-200 text-xs" x-text="selectedOrder.fiscal_address"></p>
                                        </div>
                                        </template>
                                        <template x-if="selectedOrder.billing_type === 'boleta'">
                                            <div class="col-span-2">
                                                <p class="text-[10px] text-gray-400 uppercase font-black">DNI</p>
                                                <p class="font-bold text-gray-800 dark:text-gray-200" x-text="selectedOrder.customer_dni || 'No proporcionado'"></p>
                                            </div>
                                        </template>
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

                            <!-- Total Row & Web Verification -->
                            <div class="space-y-4">
                                {{-- Regular Total Row (Hidden for web orders with pre-set payment) --}}
                                <template x-if="!(['web', 'online'].includes(selectedOrder.order_source) && selectedOrder.payment_method)">
                                    <div class="flex justify-between items-end mb-4">
                                        <span class="text-gray-500 dark:text-gray-400 font-bold">Total Final</span>
                                        <span class="text-4xl font-black text-gray-900 dark:text-white tabular-nums"
                                            x-text="formatMoney(currentTotal)"></span>
                                    </div>
                                </template>

                                {{-- Web Verification Section (Dedicated Flow) --}}
                                <template x-if="['web', 'online'].includes(selectedOrder.order_source) && selectedOrder.payment_method">
                                    <div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                                        <div class="bg-blue-50 dark:bg-blue-900/10 rounded-[2.5rem] p-8 border-2 border-blue-100 dark:border-blue-900/30">
                                            <div class="flex items-center gap-5 mb-6">
                                                <div class="bg-blue-600 rounded-2xl p-4 text-white shadow-xl shadow-blue-500/30">
                                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-600/60 dark:text-blue-400/60 mb-1">Validación Requerida</p>
                                                    <p class="text-xl font-black text-blue-900 dark:text-blue-100" x-text="'Metodo: ' + selectedOrder.payment_method.toUpperCase()"></p>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-white dark:bg-gray-800/80 rounded-3xl p-6 border border-blue-100 dark:border-blue-900/20 shadow-inner text-center">
                                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2">Total a Cobrar</p>
                                                <p class="text-5xl font-black text-blue-600 dark:text-blue-400 tabular-nums tracking-tighter" x-text="formatMoney(selectedOrder.total)"></p>
                                            </div>

                                            <div class="mt-8 flex flex-col gap-3">
                                                <button type="button" 
                                                    @click="confirmVerifyWebPayment()"
                                                    class="w-full bg-blue-600 hover:bg-black text-white font-black py-5 rounded-[2rem] shadow-2xl shadow-blue-600/30 transition-all hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                                                    <span class="text-lg">Verificar y Generar Boleta</span>
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>

                                                <button type="button"
                                                    @click="reportPaymentProblem()"
                                                    class="w-full bg-red-50 hover:bg-red-100 text-red-600 font-black py-4 rounded-2xl transition-all active:scale-95 flex items-center justify-center gap-2 border-2 border-red-100">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                    Reportar Inconsistencia
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-3 p-5 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
                                            <div class="bg-gray-200 dark:bg-gray-700 p-2 rounded-full">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <p class="text-[11px] font-bold text-gray-500 leading-relaxed">Confirma en tu app el ingreso exacto antes de continuar. El pedido se marcará como pagado.</p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                                    {{-- OLD Logic: Only for In-Person or Orders without pre-set method --}}
                                    <template x-if="!['web', 'online'].includes(selectedOrder.order_source) || !selectedOrder.payment_method">
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

                                            <form id="paymentForm" :action="`/billing/${selectedOrderId}/payment`"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="payment_method" :value="paymentMethod">
                                                <input type="hidden" name="amount_received" :value="amountReceived">
                                                <button type="button" @click="handlePayment()" :disabled="!canSubmit"
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
                                <a :href="`/billing/${selectedOrderId}/download-invoice`" target="_blank"
                                    class="w-full bg-gray-900 hover:bg-black text-white py-4 rounded-xl font-bold text-lg shadow-xl transition-all flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                    Ver Comprobante PDF
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Digital Payment Modal (Improved UI) -->
        <div x-show="showCardModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md" x-transition>
            <div @click.away="showCardModal = false"
                class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all p-10">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Seleccionar Medio</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium mt-1">Elige cómo desea pagar el cliente</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <button @click="selectDigitalPayment('yape')"
                        class="group relative p-6 rounded-[2rem] border-2 border-purple-100 dark:border-purple-900/30 hover:border-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-all flex flex-col items-center gap-4 active:scale-95">
                        <div class="w-20 h-20 bg-purple-600 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-purple-200 dark:shadow-none group-hover:rotate-6 transition-transform">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z" />
                            </svg>
                        </div>
                        <span class="font-black text-purple-700 dark:text-purple-400 tracking-wider">YAPE</span>
                    </button>

                    <button @click="selectDigitalPayment('plin')"
                        class="group relative p-6 rounded-[2rem] border-2 border-cyan-100 dark:border-cyan-900/30 hover:border-cyan-500 hover:bg-cyan-50 dark:hover:bg-cyan-900/10 transition-all flex flex-col items-center gap-4 active:scale-95">
                        <div class="w-20 h-20 bg-cyan-500 rounded-3xl flex items-center justify-center text-white shadow-xl shadow-cyan-200 dark:shadow-none group-hover:-rotate-6 transition-transform">
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71z" />
                            </svg>
                        </div>
                        <span class="font-black text-cyan-700 dark:text-cyan-400 tracking-wider">PLIN</span>
                    </button>

                    <button @click="selectDigitalPayment('card')"
                        class="col-span-2 group p-6 rounded-[2.5rem] bg-gray-50 dark:bg-gray-800/50 border-2 border-blue-50 dark:border-blue-900/20 hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all flex items-center justify-between px-10 active:scale-95">
                        <div class="flex items-center gap-5 font-black">
                            <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200 dark:shadow-none">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <div class="text-left">
                                <span class="block text-xl text-blue-800 dark:text-blue-400">Tarjeta</span>
                                <span class="text-xs text-blue-400 uppercase tracking-widest font-bold">Datáfono Físico</span>
                            </div>
                        </div>
                        <svg class="w-6 h-6 text-blue-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                <div class="mt-8 text-center">
                    <button @click="showCardModal = false"
                        class="text-gray-400 hover:text-gray-900 dark:hover:text-white font-black text-sm uppercase tracking-widest transition-colors">Volver a Caja</button>
                </div>
            </div>
        </div>

        {{-- Improved QR Payment Modal --}}
        <div x-show="showQRModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-md" x-transition>
            <div @click.away="showQRModal = false"
                class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-[3rem] overflow-hidden shadow-2xl transform transition-all">

                {{-- Header Decor --}}
                <div class="h-2 w-full" :class="paymentMethod === 'yape' ? 'bg-purple-600' : 'bg-cyan-500'"></div>

                <div class="p-10">
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-gray-100 dark:bg-gray-800 text-[10px] font-black tracking-[0.2em] text-gray-400 uppercase mb-4">
                            Transacción Digital
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 dark:text-white"
                            x-text="paymentMethod === 'yape' ? 'Pago con Yape' : 'Pago con Plin'"></h3>
                        <p class="text-gray-500 text-sm font-medium mt-1">Escanea el código para procesar el cobro</p>
                    </div>

                    {{-- Dynamic QR Container --}}
                    <div class="relative group mb-8">
                        <div class="absolute -inset-4 bg-gradient-to-tr rounded-[3rem] opacity-20 blur-2xl transition-opacity"
                             :class="paymentMethod === 'yape' ? 'from-purple-600 to-fuchsia-500' : 'from-cyan-500 to-blue-400'"></div>
                        <div class="relative bg-white p-4 rounded-[2.5rem] shadow-2xl border-4 flex flex-col items-center"
                             :class="paymentMethod === 'yape' ? 'border-purple-600' : 'border-cyan-500'">
                            
                            <template x-if="paymentMethod === 'yape'">
                                <img src="/img/yape-qr.png" alt="QR Yape" class="h-64 w-64 object-contain rounded-2xl">
                            </template>
                            
                            <template x-if="paymentMethod === 'plin'">
                                <div class="h-64 w-64 bg-gray-50 rounded-2xl flex flex-col items-center justify-center border-2 border-dashed border-cyan-200">
                                    <svg class="w-20 h-20 text-cyan-200" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M3 11h8V3H3v8zm2-6h4v4H5V5zm-2 8h8v8H3v-8zm2 2v4h4v-4H5zm8-12v8h8V3h-8zm2 2h4v4h-4V5zm0 8h2v2h-2v-2zm2 2h2v2h-2v-2zm-2 2h2v2h-2v-2zm4-4h2v4h-2v-4zm0 6h2v2h-2v-2z" />
                                    </svg>
                                    <p class="text-[10px] font-black text-cyan-500 mt-2">QR PLIN PENDIENTE</p>
                                </div>
                            </template>

                            <div class="mt-4 text-center">
                                <p class="text-xs font-black uppercase tracking-widest"
                                   :class="paymentMethod === 'yape' ? 'text-purple-600' : 'text-cyan-600'">
                                   Lincol Eulogio Huanca
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Amount Display --}}
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-3xl p-6 mb-8 border border-gray-100 dark:border-gray-800 text-center">
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mb-1">Total a recibir</p>
                        <p class="text-4xl font-black text-gray-900 dark:text-white tabular-nums"
                            x-text="formatMoney(currentTotal)"></p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex flex-col gap-3">
                        <button @click="confirmDigitalPayment()"
                            class="w-full py-5 rounded-2xl font-black text-xl text-white shadow-2xl transition-all active:scale-95 flex items-center justify-center gap-3"
                            :class="paymentMethod === 'yape' ? 'bg-purple-600 hover:bg-purple-700 shadow-purple-500/30' : 'bg-cyan-500 hover:bg-cyan-600 shadow-cyan-500/30'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Confirmar Cobro
                        </button>
                        <button @click="showQRModal = false"
                            class="w-full py-3 rounded-xl font-black text-xs text-gray-400 uppercase tracking-widest hover:text-gray-900 dark:hover:text-white transition-colors">
                            Volver a Medios
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Payment Info Modal (For Datáfono) --}}
        <div x-show="showCardInfoModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm" x-transition>
            <div @click.away="showCardInfoModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-md rounded-3xl overflow-hidden shadow-2xl transform transition-all">

                {{-- Modal Header --}}
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 p-6 text-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-1">Pago con Tarjeta</h3>
                    <p class="text-white/90 text-sm">Usar datáfono físico</p>
                </div>

                {{-- Content --}}
                <div class="p-8">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6 border border-blue-200 dark:border-blue-800">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                            <div>
                                <p class="text-sm font-bold text-blue-900 dark:text-blue-100 mb-1">Instrucciones</p>
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    Procesa el pago usando el <strong>datáfono físico</strong> del establecimiento.
                                    El cliente debe insertar, deslizar o acercar su tarjeta al dispositivo.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Amount Display --}}
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-xl p-4 mb-6">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold mb-1">Monto a Cobrar</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white"
                            x-text="formatMoney(currentTotal)"></p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="grid grid-cols-2 gap-3">
                        <button @click="showCardInfoModal = false"
                            class="px-4 py-3 rounded-xl font-bold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                            Cancelar
                        </button>
                        <button @click="confirmCardPayment()"
                            class="px-4 py-3 rounded-xl font-bold text-white bg-gray-800 hover:bg-black transition-all">
                            Confirmar Pago
                        </button>
                    </div>
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
                paymentMethod: 'cash',
                amountReceived: 0,
                showQRModal: false,
                showCardInfoModal: false,

                // Helper: Format and Send WhatsApp
                sendWhatsApp(phone, message) {
                    if (!phone) {
                        console.error("No phone number available for this order");
                        return;
                    }
                    // Clean phone: remove non-numeric
                    let cleanPhone = phone.replace(/\D/g, '');
                    // For Peru, if starts with 9 and length 9, add 51
                    if (cleanPhone.length === 9 && cleanPhone.startsWith('9')) {
                        cleanPhone = '51' + cleanPhone;
                    }
                    
                    const url = `https://wa.me/${cleanPhone}?text=${encodeURIComponent(message)}`;
                    window.open(url, '_blank');
                },

                selectOrder(id, status) {
                    this.selectedOrderId = id;
                    // Find the full order object from the initial data
                    // Note: In a real app we might fetch via API, but here we passed the collection.
                    // However, Blade passed readyOrders as JSON.
                    this.selectedOrder = this.orders.find(o => o.id === id);

                    this.currentTotal = parseFloat(this.selectedOrder.total);
                    this.selectedOrderPaymentStatus = status;

                    // Auto-select the payment method that the customer chose
                    // This improves cashier workflow by pre-filling the payment method
                    if (this.selectedOrder.payment_method) {
                        // Map the payment method to the correct format
                        const customerMethod = this.selectedOrder.payment_method.toLowerCase();

                        // If customer chose a digital method (yape, plin, card), pre-select it
                        if (['yape', 'plin', 'card'].includes(customerMethod)) {
                            this.paymentMethod = customerMethod;
                        } else if (customerMethod === 'cash' || customerMethod === 'efectivo') {
                            this.paymentMethod = 'cash';
                        } else {
                            // Default to empty if unknown method
                            this.paymentMethod = '';
                        }
                    } else {
                        // No payment method chosen by customer, leave empty
                        this.paymentMethod = '';
                    }

                    this.amountReceived = '';
                    this.showCardModal = false;
                    this.showQRModal = false;
                    this.showCardInfoModal = false;
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

                    // Show appropriate modal based on payment method
                    if (subMethod === 'yape' || subMethod === 'plin') {
                        this.showQRModal = true;
                    } else if (subMethod === 'card') {
                        this.showCardInfoModal = true;
                    }
                },

                confirmDigitalPayment() {
                    // Close QR modal and submit payment
                    this.showQRModal = false;
                    // Submit the form
                    this.submitPayment();
                },

                confirmCardPayment() {
                    // Close card info modal and submit payment
                    this.showCardInfoModal = false;
                    // Submit the form
                    this.submitPayment();
                },

                handlePayment() {
                    // Decide which modal to show based on payment method
                    if (this.paymentMethod === 'yape' || this.paymentMethod === 'plin') {
                        // Show QR modal for Yape/Plin
                        this.showQRModal = true;
                    } else if (this.paymentMethod === 'card') {
                        // Show datáfono info modal for Card
                        this.showCardInfoModal = true;
                    } else if (this.paymentMethod === 'cash') {
                        // For cash, submit directly (no modal needed)
                        this.submitPayment();
                    }
                },

                // Action: Confirm verify web payment with visual check
                confirmVerifyWebPayment() {
                    if (!this.selectedOrder) return;

                    const total = this.formatMoney(this.selectedOrder.total);
                    const method = this.selectedOrder.payment_method.toUpperCase();
                    const customerName = this.selectedOrder.customer_name || 'Cliente';
                    const orderNum = this.selectedOrder.order_number;

                    Swal.fire({
                        title: '<span class="font-black text-2xl uppercase tracking-tighter">¿Monto recibido?</span>',
                        html: `
                            <div class="py-4 font-bold text-gray-600 dark:text-gray-300">
                                <p class="mb-4">Por favor, confirma que has recibido el monto exacto en tu app de ${method}:</p>
                                <div class="bg-emerald-50 dark:bg-emerald-900/30 p-6 rounded-[2rem] border-2 border-emerald-100 dark:border-emerald-800">
                                    <p class="text-[10px] text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">Monto Esperado</p>
                                    <p class="text-4xl font-black text-emerald-700 dark:text-emerald-300 tabular-nums">${total}</p>
                                </div>
                            </div>
                        `,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, recibido correctamente',
                        cancelButtonText: 'Aún no',
                        background: document.documentElement.classList.contains('dark') ? '#111827' : '#FFFFFF',
                        color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#111827',
                        confirmButtonColor: '#059669',
                        cancelButtonColor: '#EF4444',
                        customClass: {
                            popup: 'rounded-[3rem] border-none shadow-2xl overflow-hidden',
                            confirmButton: 'rounded-2xl px-6 py-3 font-black text-sm uppercase tracking-widest shadow-lg shadow-emerald-500/20 active:scale-95 transition-all outline-none',
                            cancelButton: 'rounded-2xl px-6 py-3 font-black text-sm uppercase tracking-widest shadow-lg shadow-red-500/20 active:scale-95 transition-all outline-none border-none'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // 1. Prepare data
                            const method = this.selectedOrder.payment_method;
                            const total = this.selectedOrder.total;
                            
                            // 2. Notify via WhatsApp
                            const msg = `¡Hola ${customerName}! Hemos verificado tu pago de ${this.formatMoney(total)} por tu pedido ${orderNum}. Tu pedido ya está en preparación y pronto estará en camino. ¡Gracias por tu compra!`;
                            this.sendWhatsApp(this.selectedOrder.customer_phone, msg);

                            // 3. Process via AJAX (Reliable)
                            fetch(`/billing/${this.selectedOrderId}/payment`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    payment_method: method,
                                    amount_received: total
                                })
                            })
                            .then(response => {
                                if (response.ok) {
                                    window.location.reload();
                                } else {
                                    Swal.fire('Error', 'No se pudo procesar el pago en el servidor.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Fallo de conexión.', 'error');
                            });
                        }
                    });
                },

                // Action: Report problem with payment
                reportPaymentProblem() {
                    if (!this.selectedOrder) return;

                    const customerName = this.selectedOrder.customer_name || 'Cliente';
                    const orderNum = this.selectedOrder.order_number;

                    Swal.fire({
                        title: '<span class="font-black text-xl uppercase text-red-600">Reportar Problema</span>',
                        text: 'Describe el inconveniente (ej. Monto incompleto, no figura en app):',
                        input: 'textarea',
                        inputPlaceholder: 'Escribe aquí el motivo...',
                        showCancelButton: true,
                        confirmButtonText: 'Reportar Problema',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#EF4444',
                        background: document.documentElement.classList.contains('dark') ? '#111827' : '#FFFFFF',
                        color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#111827',
                        customClass: {
                            popup: 'rounded-[2.5rem]',
                            confirmButton: 'rounded-xl px-6 py-3 font-bold',
                            cancelButton: 'rounded-xl px-6 py-3 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            const reason = result.value;
                            
                            fetch(`/billing/${this.selectedOrderId}/reject-payment`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ reason: reason })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Notify via WhatsApp
                                    const msg = `Hola ${customerName}, te saludamos de nuestro restaurante. Tenemos un inconveniente con el pago de tu pedido ${orderNum}. Motivo: ${reason}. Por favor comunícate con nosotros para regularizarlo. Gracias.`;
                                    this.sendWhatsApp(this.selectedOrder.customer_phone, msg);

                                    Swal.fire({
                                        title: 'Reportado',
                                        text: 'Inconsistencia reportada y cliente notificado.',
                                        icon: 'success',
                                        confirmButtonColor: '#3B82F6',
                                        customClass: { popup: 'rounded-3xl' }
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            });
                        }
                    });
                },

                // Logic: Submit payment to backend
                submitPayment() {
                    if (this.canSubmit) {
                        document.getElementById('paymentForm').submit();
                    }
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
