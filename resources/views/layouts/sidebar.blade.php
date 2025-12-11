<!-- Mobile Sidebar Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-20 md:hidden"
    style="display: none;">
</div>

<!-- Mobile Sidebar -->
<div x-show="sidebarOpen" @click.away="sidebarOpen = false"
    x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
    class="fixed inset-y-0 left-0 flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-30 md:hidden"
    style="display: none;">

    <!-- Mobile Header with Close Button -->
    <div class="flex items-center justify-between h-16 border-b border-gray-200 dark:border-gray-700 px-4">
        <div class="flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            <span class="text-xl font-bold text-gray-800 dark:text-gray-100">RestaurantOS</span>
        </div>
        <button @click="sidebarOpen = false"
            class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">

            {{-- Dashboard: Everyone with dashboard permission --}}
            @permission('dashboard')
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>
            @endpermission

            {{-- Orders: Everyone with orders permission --}}
            @permission('orders')
                <a href="{{ route('orders.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('orders.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    Pedidos
                </a>
            @endpermission

            <a href="{{ route('reservations.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('reservations.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Reservaciones
            </a>

            <a href="{{ route('tables.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('tables.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                Mesas
            </a>

            <a href="{{ route('blogs.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('blogs.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                Blogs
            </a>

            {{-- Kitchen/KDS: Only with kitchen permission --}}
            @permission('kitchen')
                <a href="{{ route('kitchen.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('kitchen.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                        </path>
                    </svg>
                    KDS - Cocina
                </a>
            @endpermission

            {{-- Menu: Only with menu permission --}}
            @permission('menu')
                <a href="{{ route('menu.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('menu.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Menú
                </a>

                <a href="{{ route('categories.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('categories.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Categorías
                </a>

                <a href="{{ route('promotions.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('promotions.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Promociones
                </a>
            @endpermission

            {{-- Inventory: Only with inventory permission --}}
            @permission('inventory')
                <a href="{{ route('inventory.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('inventory.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Inventario
                </a>
            @endpermission

            {{-- Reports: Only with reports permission --}}
            @permission('reports')
                <a href="{{ route('reports.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reports.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Reportes
                </a>
            @endpermission

            {{-- Billing: Only with billing permission --}}
            @permission('billing')
                <a href="{{ route('billing.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('billing.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Caja y Facturación
                </a>
            @endpermission

            {{-- Settings: Only with settings permission or admin --}}
            @permission('settings')
                <a href="{{ route('roles.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('roles.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    Roles y Permisos
                </a>

                <a href="{{ route('staff.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('staff.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Personal
                </a>

                <a href="{{ route('settings.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('settings.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configuración
                </a>
            @endpermission
        </nav>
    </div>
</div>

<!-- Desktop Sidebar -->
<div class="hidden md:flex flex-col w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-center h-16 border-b border-gray-200 dark:border-gray-700 px-4">
        <div class="flex items-center gap-2">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                </path>
            </svg>
            <span class="text-xl font-bold text-gray-800 dark:text-gray-100">RestaurantOS</span>
        </div>
    </div>
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">

            {{-- Dashboard: Everyone with dashboard permission --}}
            @permission('dashboard')
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Dashboard
                </a>
            @endpermission

            {{-- Orders: Everyone with orders permission --}}
            @permission('orders')
                <a href="{{ route('orders.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('orders.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                        </path>
                    </svg>
                    Pedidos
                </a>
            @endpermission

            <a href="{{ route('reservations.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('reservations.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Reservaciones
            </a>

            <a href="{{ route('tables.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('tables.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                Mesas
            </a>

            <a href="{{ route('blogs.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('blogs.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                Blogs
            </a>

            {{-- Kitchen/KDS: Only with kitchen permission --}}
            @permission('kitchen')
                <a href="{{ route('kitchen.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('kitchen.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                        </path>
                    </svg>
                    KDS - Cocina
                </a>
            @endpermission

            {{-- Menu: Only with menu permission --}}
            @permission('menu')
                <a href="{{ route('menu.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('menu.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Menú
                </a>

                <a href="{{ route('categories.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('categories.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Categorías
                </a>

                <a href="{{ route('promotions.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('promotions.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Promociones
                </a>
            @endpermission

            {{-- Inventory: Only with inventory permission --}}
            @permission('inventory')
                <a href="{{ route('inventory.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('inventory.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    Inventario
                </a>
            @endpermission

            {{-- Billing: Only with billing permission --}}
            @permission('billing')
                <a href="{{ route('billing.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('billing.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Caja y Facturación
                </a>
            @endpermission

            {{-- Reports: Only with reports permission --}}
            @permission('reports')
                <a href="{{ route('reports.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reports.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Reportes
                </a>
            @endpermission

            {{-- Settings: Only with settings permission or admin --}}
            @permission('settings')
                <a href="{{ route('roles.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('roles.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    Roles y Permisos
                </a>

                <a href="{{ route('staff.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('staff.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Personal
                </a>

                <a href="{{ route('settings.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('settings.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Configuración
                </a>
            @endpermission
        </nav>
    </div>
</div>
