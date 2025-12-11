<x-app-layout>
    <div x-data="billingManager" class="space-y-6">
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
                    <div @click="selectOrder({{ $order->id }}, {{ $order->total }})"
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
                            <div class="grid grid-cols-2 gap-3 text-md">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-300 text-xs">Cliente / Mesero</p>
                                    @if ($order->order_source === 'waiter')
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            Mesa: {{ $order->table_number }} ({{ $order->waiter->name ?? 'Mesero' }})
                                        </p>
                                    @else
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $order->customer_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Items Summary -->
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Items: {{ $order->items->count() }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($order->items->take(3) as $item)
                                    <span
                                        class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $item->quantity }}x {{ $item->product->name }}
                                    </span>
                                @endforeach
                                @if ($order->items->count() > 3)
                                    <span class="text-xs text-gray-400 px-2 py-1">+{{ $order->items->count() - 3 }}
                                        más</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm p-12 text-center">
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
                <div
                    class="mb-6 bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl p-6 border-2 border-green-200 dark:bg-none dark:bg-gray-700/50 dark:border-gray-600">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total a Cobrar</p>
                    <p class="text-4xl font-black text-gray-900 dark:text-white" x-text="formatMoney(currentTotal)"></p>
                </div>

                <!-- Payment Buttons -->
                <template x-if="selectedOrderId">
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <button @click="setPaymentMethod('cash')" type="button"
                            :class="paymentMethod === 'cash' ?
                                'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-500' :
                                'border-gray-200 hover:bg-gray-50 text-gray-600 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                            class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border-2 font-bold transition-all dark:bg-gray-800">
                            <!-- Icon Cash -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Efectivo
                        </button>
                        <button @click="openCardModal()" type="button"
                            :class="['card', 'yape', 'plin'].includes(paymentMethod) ?
                                'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-500' :
                                'border-gray-200 hover:bg-gray-50 text-gray-600 dark:border-gray-600 dark:text-gray-400 dark:hover:bg-gray-700'"
                            class="flex flex-col items-center justify-center gap-2 p-3 rounded-xl border-2 font-bold transition-all dark:bg-gray-800">
                            <!-- Icon Card -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Tarjeta / Digital
                        </button>
                    </div>
                </template>

                <!-- Amount Received (only for cash) -->
                <template x-if="paymentMethod === 'cash' && selectedOrderId">
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 dark:text-gray-200">Monto
                            Recibido</label>
                        <input type="number" x-model.number="amountReceived" step="0.01" min="0"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 text-lg font-bold dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
                            placeholder="0.00">
                        <template x-if="change > 0">
                            <p class="mt-2 text-sm text-green-600 font-semibold dark:text-green-200">
                                Vuelto: <span x-text="formatMoney(change)"></span>
                            </p>
                        </template>
                        <template x-if="amountReceived > 0 && amountReceived < currentTotal">
                            <p class="mt-2 text-sm text-red-600 font-semibold dark:text-red-200">
                                Monto insuficiente
                            </p>
                        </template>
                    </div>
                </template>

                <!-- Execute Payment Form -->
                <template x-if="selectedOrderId">
                    <form :action="`/billing/${selectedOrderId}/payment`" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="payment_method" :value="paymentMethod">
                        <input type="hidden" name="amount_received"
                            :value="paymentMethod === 'cash' ? amountReceived : currentTotal">

                        <button type="submit" :disabled="!canSubmit"
                            :class="!canSubmit ? 'opacity-50 cursor-not-allowed bg-gray-400 dark:bg-gray-600' :
                                'bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-500'"
                            class="w-full text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 dark:shadow-none transition-all text-lg">
                            <span x-text="submitButtonText"></span>
                        </button>
                    </form>
                </template>

                <template x-if="!selectedOrderId">
                    <button disabled
                        class="w-full bg-gray-300 text-gray-500 font-bold py-3.5 rounded-xl text-lg cursor-not-allowed dark:bg-gray-700 dark:text-gray-200">
                        Selecciona un pedido para cobrar
                    </button>
                </template>
            </div>
        </div>

        <!-- Digital Payment Modal -->
        <div x-show="showCardModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition>
            <div @click.away="showCardModal = false"
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md p-6 transform transition-all">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Seleccione Método de Pago</h3>

                <div class="grid grid-cols-1 gap-3">
                    <button @click="selectDigitalPayment('card')"
                        class="flex items-center p-4 border-2 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-700 transition-all gap-4 group">
                        <div class="bg-blue-100 p-3 rounded-lg text-blue-600 group-hover:bg-blue-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-900 dark:text-white">Tarjeta Débito/Crédito</p>
                            <p class="text-xs text-gray-500">Visa, Mastercard, Amex</p>
                        </div>
                    </button>

                    <button @click="selectDigitalPayment('yape')"
                        class="flex items-center p-4 border-2 rounded-xl hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-gray-700 transition-all gap-4 group">
                        <div
                            class="bg-purple-100 p-3 rounded-lg text-purple-600 group-hover:bg-purple-200 font-bold text-xs">
                            YAPE
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-900 dark:text-white">Yape</p>
                            <p class="text-xs text-gray-500">Pago con QR o número</p>
                        </div>
                    </button>

                    <button @click="selectDigitalPayment('plin')"
                        class="flex items-center p-4 border-2 rounded-xl hover:border-cyan-500 hover:bg-cyan-50 dark:hover:bg-gray-700 transition-all gap-4 group">
                        <div
                            class="bg-cyan-100 p-3 rounded-lg text-cyan-600 group-hover:bg-cyan-200 font-bold text-xs">
                            PLIN
                        </div>
                        <div class="text-left">
                            <p class="font-bold text-gray-900 dark:text-white">Plin</p>
                            <p class="text-xs text-gray-500">Pago con QR o número</p>
                        </div>
                    </button>

                    <button @click="showCardModal = false"
                        class="mt-2 w-full py-3 text-gray-500 font-bold hover:text-gray-700 dark:text-gray-400">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('billingManager', () => ({
                selectedOrderId: null,
                currentTotal: 0,
                paymentMethod: '', // cash, card, yape, plin
                amountReceived: 0,
                showCardModal: false,

                selectOrder(id, total) {
                    this.selectedOrderId = id;
                    this.currentTotal = parseFloat(total);
                    this.paymentMethod = '';
                    this.amountReceived = 0;
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
                    return Math.max(0, this.amountReceived - this.currentTotal);
                },

                get canSubmit() {
                    if (!this.selectedOrderId) return false;
                    if (!this.paymentMethod) return false;
                    if (this.paymentMethod === 'cash') {
                        return this.amountReceived >= this.currentTotal;
                    }
                    return true;
                },

                get submitButtonText() {
                    if (!this.paymentMethod) return 'Cobrar';
                    const methodLabels = {
                        'cash': 'Efectivo',
                        'card': 'Tarjeta',
                        'yape': 'Yape',
                        'plin': 'Plin'
                    };
                    const label = methodLabels[this.paymentMethod] || '';
                    return `Cobrar ${this.formatMoney(this.currentTotal)} (${label})`;
                },

                formatMoney(amount) {
                    return 'S/ ' + parseFloat(amount).toFixed(2);
                }
            }))
        });
    </script>
</x-app-layout>
