<nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="px-6 py-3">
        <div class="flex justify-between items-center h-16">
            <!-- Left Side -->
            <div class="flex items-center">
                <!-- Mobile Toggle -->
                <div class="-me-2 flex items-center md:hidden mr-4">
                    <button @click="sidebarOpen = ! sidebarOpen"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>

            <!-- Global Search -->
            <div class="flex-1 max-w-xl px-4 dark:text-gray-100" x-data="globalSearch()" @click.away="closeResults">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-100" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="query" @input.debounce.300ms="search" @focus="open = true"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:placeholder-gray-400 focus:border-blue-300 focus:shadow-outline-blue sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Buscar pedido, cliente o producto..." autocomplete="off">

                    <!-- Search Results Dropdown -->
                    <div x-show="open && (hasResults || loading)"
                        class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg rounded-md py-1 border border-gray-100 dark:border-gray-700"
                        style="display: none;">

                        <div x-show="loading" class="px-4 py-2 text-sm text-gray-500">Buscando...</div>

                        <template x-if="results.orders.length > 0">
                            <div>
                                <div
                                    class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 dark:bg-gray-700">
                                    Pedidos
                                </div>
                                <template x-for="order in results.orders" :key="order.id">
                                    <a :href="order.url"
                                        class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        <div class="font-medium" x-text="order.title"></div>
                                        <div class="text-xs text-gray-500" x-text="order.subtitle"></div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <template x-if="results.products.length > 0">
                            <div>
                                <div
                                    class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50 dark:bg-gray-700 border-t border-gray-100 dark:border-gray-700">
                                    Productos
                                </div>
                                <template x-for="product in results.products" :key="product.id">
                                    <a :href="product.url"
                                        class="block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        <div class="font-medium" x-text="product.title"></div>
                                        <div class="text-xs text-gray-500" x-text="product.subtitle"></div>
                                    </a>
                                </template>
                            </div>
                        </template>

                        <div x-show="!loading && !hasResults" class="px-4 py-2 text-sm text-gray-500">
                            No se encontraron resultados
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center">
                <!-- Notifications -->
                <div x-data="notificationSystem()" x-init="init()" class="relative">
                    <x-dropdown align="right" width="w-[350px]">
                        <x-slot name="trigger">
                            <button
                                class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white relative rounded-lg dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                <span x-show="unreadCount > 0"
                                    class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-800"></span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div
                                class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <span
                                    class="text-sm font-semibold text-gray-700 dark:text-gray-200">Notificaciones</span>
                                <button x-show="unreadCount > 0" @click.stop="markAllAsRead"
                                    class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                    Marcar todo leido
                                </button>
                            </div>

                            <div class="max-h-96 overflow-y-auto">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div
                                        class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700 transition-colors group">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex items-start gap-3 flex-1">
                                                <div class="flex-shrink-0 mt-1">
                                                    <!-- Icon based on type -->
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center"
                                                        :class="notification.data.type === 'new_order' ?
                                                            'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600'">
                                                        <svg x-show="notification.data.type === 'new_order'"
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                        </svg>
                                                        <svg x-show="notification.data.type !== 'new_order'"
                                                            class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                                        x-text="notification.data.message"></p>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <p class="text-xs text-gray-500"
                                                            x-text="formatDate(notification.created_at)"></p>
                                                        <template x-if="notification.data.customer">
                                                            <span class="text-xs text-gray-400">• <span
                                                                    x-text="notification.data.customer"></span></span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>

                                            <button @click="viewOrder(notification)"
                                                class="flex-shrink-0 p-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors"
                                                title="Ver Pedido">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="notifications.length === 0"
                                    class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                                    No hay notificaciones nuevas
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <button
                    class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white ml-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </button>

                <!-- Theme Toggle -->
                <button id="theme-toggle"
                    class="p-2 text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white ml-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <!-- Sun icon (shown in dark mode) -->
                    <svg id="theme-toggle-light-icon" class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                            fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                    <!-- Moon icon (shown in light mode) -->
                    <svg id="theme-toggle-dark-icon" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                </button>

                <!-- Profile Dropdown -->
                <div class="ms-4 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition ease-in-out duration-150">
                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3B82F6&color=fff&bold=true"
                                    alt="{{ Auth::user()->name }}" />
                                <div class="text-left hidden md:block">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ Auth::user()->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                        {{ Auth::user()->role }}</div>
                                </div>
                                <svg class="hidden md:block h-4 w-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    {{ __('Mi Perfil') }}
                                </div>
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        {{ __('Cerrar Sesión') }}
                                    </div>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function notificationSystem() {
        return {
            notifications: [],
            unreadCount: 0,
            init() {
                this.fetchNotifications();
                setInterval(() => {
                    this.fetchNotifications();
                }, 30000); // Poll every 30 seconds
            },
            async fetchNotifications() {
                try {
                    const response = await fetch('{{ route('notifications.index') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                } catch (error) {
                    console.error('Error fetching notifications:', error);
                }
            },
            async viewOrder(notification) {
                // First mark as read
                await this.markAsRead(notification.id);

                // Then redirect based on type
                if (notification.data.type === 'new_order') {
                    window.location.href = `/orders/${notification.data.order_id}`;
                }
            },
            async markAsRead(id) {
                try {
                    await fetch(`/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });
                    this.fetchNotifications();
                } catch (error) {
                    console.error('Error marking as read:', error);
                }
            },
            async markAllAsRead() {
                try {
                    await fetch('{{ route('notifications.read-all') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });
                    this.fetchNotifications();
                } catch (error) {
                    console.error('Error marking all as read:', error);
                }
            },
            formatDate(dateString) {
                const date = new Date(dateString);
                return new Intl.NumberFormat('es-PE', {
                        style: 'decimal',
                        minimumIntegerDigits: 2
                    }).format(date.getHours()) + ':' +
                    new Intl.NumberFormat('es-PE', {
                        style: 'decimal',
                        minimumIntegerDigits: 2
                    }).format(date.getMinutes());
            }
        }
    }
</script>

<script>
    function globalSearch() {
        return {
            query: '',
            results: {
                orders: [],
                products: []
            },
            open: false,
            loading: false,

            get hasResults() {
                return this.results.orders.length > 0 || this.results.products.length > 0;
            },

            closeResults() {
                this.open = false;
            },

            async search() {
                if (this.query.length < 2) {
                    this.results = {
                        orders: [],
                        products: []
                    };
                    this.open = false;
                    return;
                }

                this.loading = true;
                this.open = true;

                try {
                    const response = await fetch(
                        `{{ route('global-search') }}?query=${encodeURIComponent(this.query)}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                    this.results = await response.json();
                } catch (error) {
                    console.error('Search error:', error);
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
