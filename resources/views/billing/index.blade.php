<x-app-layout>
    <div x-data="billingManager()" class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight dark:text-white">Caja y Facturación</h1>
                <p class="text-gray-500 dark:text-gray-400">Gestión de cobros y cierre de caja</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Payable Orders List -->
            <div class="lg:col-span-2 space-y-4">
                <h3 class="font-bold text-gray-800 text-lg dark:text-white">Pedidos por Cobrar</h3>

                @forelse($readyOrders as $order)
                    <div @click="selectOrder({{ $order->id }})"
                        :class="selectedOrderId === {{ $order->id }} ?
                            'border-blue-500 bg-blue-50 dark:bg-blue-900/10 dark:border-blue-500' :
                            'border-gray-100 dark:border-gray-700'"
                        class="bg-white rounded-2xl border-2 shadow-sm p-5 hover:border-blue-200 transition-all cursor-pointer dark:bg-gray-800 dark:hover:border-blue-500/50">

                        <!-- Header: Order Info -->
                        <div
                            class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100 dark:border-gray-600">
                            <div class="flex items-center gap-4">
                                <div
                                    class="bg-green-100 text-green-600 w-14 h-14 rounded-xl flex items-center justify-center font-bold text-lg dark:bg-green-600 dark:text-green-200">
                                    @if ($order->table_number)
                                        {{ $order->table_number }}
                                    @else
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-lg dark:text-white">
                                        {{ $order->order_number }}
                                        @if ($order->table_number)
                                            - Mesa {{ $order->table_number }}
                                        @else
                                            - {{ ucfirst($order->order_type) }}
                                        @endif
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ formatTime($order->created_at, true) }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="block text-2xl font-black text-green-600 dark:text-green-200">{{ formatMoney($order->total) }}</span>
                                <span
                                    class="text-xs font-bold px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-600 dark:text-green-200">Listo</span>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="mb-4 bg-gray-50  rounded-xl p-3 dark:bg-gray-700 dark:border-gray-600">
                            <h5 class="text-xs font-semibold text-gray-500 dark:text-gray-200 uppercase mb-2">
                                Información del Cliente</h5>
                            <div class="grid grid-cols-2 gap-3 text-md">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-300 text-xs">Nombre</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $order->customer_name }}
                                    </p>
                                </div>
                                @if ($order->customer_phone)
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-300 text-xs">Teléfono</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->customer_phone }}</p>
                                    </div>
                                @endif
                                @if ($order->customer_email)
                                    <div class="col-span-2">
                                        <p class="text-gray-500 dark:text-gray-300 text-xs">Email</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->customer_email }}</p>
                                    </div>
                                @endif
                                @if ($order->delivery_address)
                                    <div class="col-span-2">
                                        <p class="text-gray-500 dark:text-gray-300 text-xs">Dirección de Entrega</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->delivery_address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div>
                            <h5 class="text-xs font-semibold text-gray-500 uppercase dark:text-gray-300 mb-2">Productos
                                ({{ $order->items->count() }})
                            </h5>
                            <div class="space-y-2">
                                @foreach ($order->items as $item)
                                    <div
                                        class="flex justify-between items-start text-sm bg-white dark:bg-gray-700 rounded-lg p-2 border border-gray-100 dark:border-gray-600">
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                <span
                                                    class="font-bold rounded-lg bg-blue-500 text-white  px-2 py-1 mr-2">{{ $item->quantity }}</span>
                                                {{ $item->product->name }}
                                            </p>
                                            @if ($item->notes)
                                                <p class="text-xs text-orange-600 italic mt-1 dark:text-orange-400">
                                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                        </path>
                                                    </svg>
                                                    {{ $item->notes }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right ml-3">
                                            <p class="font-bold text-gray-900 dark:text-white">
                                                {{ formatMoney($item->subtotal) }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ formatMoney($item->unit_price) }} c/u
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hay pedidos listos</h3>
                        <p class="text-gray-500 dark:text-gray-400">Los pedidos listos para cobrar aparecerán aquí</p>
                    </div>
                @endforelse
            </div>

            <!-- Payment Panel -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-lg p-6 flex flex-col h-fit sticky top-6">
                <h3 class="font-bold text-gray-800 dark:text-white text-xl mb-6">Detalle de Cobro</h3>

                <!-- Total Display -->
                <!-- Total Display -->
                <div
                    class="mb-6 bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-6 border-2 border-green-200 dark:bg-none dark:bg-gray-700/50 dark:border-gray-600">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total a Cobrar</p>
                    <p class="text-4xl font-black text-gray-900 dark:text-white" x-text="formatMoney(total)"></p>
                </div>

                <!-- Payment Method Selection -->
                <div class="grid grid-cols-2 gap-3 mb-6">
                    <button @click="paymentMethod = 'cash'" type="button"
                        :class="paymentMethod === 'cash' ?
                            'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-500' :
                            'border-gray-200 hover:bg-gray-50 text-gray-600 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                        class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border-2 font-bold transition-all dark:bg-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Efectivo
                    </button>
                    <button @click="paymentMethod = 'card'" type="button"
                        :class="paymentMethod === 'card' ?
                            'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-500' :
                            'border-gray-200 hover:bg-gray-50 text-gray-600 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                        class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border-2 font-bold transition-all dark:bg-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Tarjeta
                    </button>
                </div>

                <!-- Amount Received (only for cash) -->
                <template x-if="paymentMethod === 'cash' && selectedOrder">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">Monto
                            Recibido</label>
                        <input type="number" x-model.number="amountReceived" step="0.01" min="0"
                            @input="amountReceived = parseFloat($event.target.value) || 0"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 text-lg font-bold dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
                            placeholder="0.00">
                        <template x-if="change > 0">
                            <p class="mt-2 text-sm text-green-600 font-semibold dark:text-green-200">
                                Vuelto: <span x-text="formatMoney(change)"></span>
                            </p>
                        </template>
                        <template x-if="amountReceived > 0 && amountReceived < total">
                            <p class="mt-2 text-sm text-red-600 font-semibold dark:text-red-200">
                                Monto insuficiente
                            </p>
                        </template>
                    </div>
                </template>

                <!-- Process Payment Button -->
                <template x-if="selectedOrder">
                    <form :action="`/billing/${selectedOrderId}/payment`" method="POST">
                        @csrf
                        <input type="hidden" name="payment_method" :value="paymentMethod">
                        <input type="hidden" name="amount_received"
                            :value="paymentMethod === 'cash' ? amountReceived : total">
                        <button type="submit"
                            :disabled="!paymentMethod || (paymentMethod === 'cash' && (amountReceived <= 0 || amountReceived <
                                total))"
                            :class="!paymentMethod || (paymentMethod === 'cash' && (amountReceived <= 0 || amountReceived <
                                    total)) ? 'opacity-50 cursor-not-allowed bg-gray-400 dark:bg-gray-600' :
                                'bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-500'"
                            class="w-full text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 dark:shadow-none transition-all text-lg">
                            <span x-text="'Cobrar ' + formatMoney(total)"></span>
                        </button>
                    </form>
                </template>

                <template x-if="!selectedOrder">
                    <button disabled
                        class="w-full bg-gray-300 text-gray-500 font-bold py-3.5 rounded-xl text-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-200">
                        Selecciona un pedido
                    </button>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
