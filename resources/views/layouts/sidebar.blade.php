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
            <i data-lucide="utensils-crossed" class="w-8 h-8 text-blue-600"></i>
            <span class="text-xl font-bold text-gray-800 dark:text-gray-100">RestaurantOS</span>
        </div>
        <button @click="sidebarOpen = false"
            class="p-2 rounded-md text-gray-400 hover:text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Mobile Navigation -->
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">

            {{-- DASHBOARD --}}
            @permission('dashboard')
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
            @endpermission

            {{-- SECCIÓN: OPERACIONES --}}
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Operaciones</p>
            </div>

            @permission('orders')
                <a href="{{ route('orders.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('orders.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="clipboard-list" class="w-5 h-5 mr-3"></i>
                    Pedidos
                </a>
            @endpermission

            @permission('kitchen')
                <a href="{{ route('kitchen.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('kitchen.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="chef-hat" class="w-5 h-5 mr-3"></i>
                    KDS - Cocina
                </a>
            @endpermission

            @permission('reservations')
                <a href="{{ route('reservations.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reservations.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i>
                    Reservaciones
                </a>
            @endpermission

            @permission('tables')
                <a href="{{ route('tables.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('tables.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="armchair" class="w-5 h-5 mr-3"></i>
                    Mesas
                </a>
            @endpermission

            {{-- SECCIÓN: CONTENIDO --}}
            @permission('menu')
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        Contenido</p>
                </div>

                <a href="{{ route('menu.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('menu.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="utensils-crossed" class="w-5 h-5 mr-3"></i>
                    Menú
                </a>

                <a href="{{ route('categories.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('categories.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="tags" class="w-5 h-5 mr-3"></i>
                    Categorías
                </a>

                <a href="{{ route('promotions.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('promotions.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="badge-percent" class="w-5 h-5 mr-3"></i>
                    Promociones
                </a>
            @endpermission

            @permission('blogs')
                <a href="{{ route('blogs.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('blogs.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="newspaper" class="w-5 h-5 mr-3"></i>
                    Blogs
                </a>
            @endpermission

            {{-- SECCIÓN: GESTIÓN --}}
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Gestión
                </p>
            </div>

            @permission('inventory')
                <a href="{{ route('inventory.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('inventory.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                    Inventario
                </a>
            @endpermission

            @permission('billing')
                <a href="{{ route('billing.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('billing.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="receipt-text" class="w-5 h-5 mr-3"></i>
                    Caja y Facturación
                </a>
            @endpermission

            @permission('reports')
                <a href="{{ route('reports.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reports.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                    Reportes
                </a>
            @endpermission

            <a href="{{ route('customers.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('customers.*') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                <i data-lucide="user-round-check" class="w-5 h-5 mr-3"></i>
                Clientes
            </a>

            {{-- SECCIÓN: ADMINISTRACIÓN --}}
            @permission('settings')
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        Administración</p>
                </div>

                <a href="{{ route('roles.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('roles.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="shield-check" class="w-5 h-5 mr-3"></i>
                    Roles y Permisos
                </a>

                <a href="{{ route('staff.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('staff.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                    Personal
                </a>

                <a href="{{ route('settings.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('settings.index') ? 'text-white bg-blue-600' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
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
            <i data-lucide="utensils-crossed" class="w-8 h-8 text-blue-600"></i>
            <span class="text-xl font-bold text-gray-800 dark:text-gray-100">RestaurantOS</span>
        </div>
    </div>
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-2 py-4 space-y-1">

            {{-- DASHBOARD --}}
            @permission('dashboard')
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
            @endpermission

            {{-- SECCIÓN: OPERACIONES --}}
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                    Operaciones</p>
            </div>

            @permission('orders')
                <a href="{{ route('orders.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('orders.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="clipboard-list" class="w-5 h-5 mr-3"></i>
                    Pedidos
                </a>
            @endpermission

            @permission('kitchen')
                <a href="{{ route('kitchen.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('kitchen.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="chef-hat" class="w-5 h-5 mr-3"></i>
                    KDS - Cocina
                </a>
            @endpermission

            @permission('reservations')
                <a href="{{ route('reservations.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reservations.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i>
                    Reservaciones
                </a>
            @endpermission

            @permission('tables')
                <a href="{{ route('tables.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('tables.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="armchair" class="w-5 h-5 mr-3"></i>
                    Mesas
                </a>
            @endpermission

            {{-- SECCIÓN: CONTENIDO --}}
            @permission('menu')
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        Contenido</p>
                </div>

                <a href="{{ route('menu.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('menu.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="utensils-crossed" class="w-5 h-5 mr-3"></i>
                    Menú
                </a>

                <a href="{{ route('categories.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('categories.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="tags" class="w-5 h-5 mr-3"></i>
                    Categorías
                </a>

                <a href="{{ route('promotions.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('promotions.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="badge-percent" class="w-5 h-5 mr-3"></i>
                    Promociones
                </a>
            @endpermission

            @permission('blogs')
                <a href="{{ route('blogs.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('blogs.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="newspaper" class="w-5 h-5 mr-3"></i>
                    Blogs
                </a>
            @endpermission

            {{-- SECCIÓN: GESTIÓN --}}
            <div class="pt-4 pb-2">
                <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Gestión
                </p>
            </div>

            @permission('inventory')
                <a href="{{ route('inventory.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('inventory.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="package" class="w-5 h-5 mr-3"></i>
                    Inventario
                </a>
            @endpermission

            @permission('billing')
                <a href="{{ route('billing.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('billing.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="receipt-text" class="w-5 h-5 mr-3"></i>
                    Caja y Facturación
                </a>
            @endpermission

            @permission('reports')
                <a href="{{ route('reports.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('reports.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i>
                    Reportes
                </a>
            @endpermission

            <a href="{{ route('customers.index') }}"
                class="flex items-center px-4 py-3 {{ request()->routeIs('customers.*') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                <i data-lucide="user-round-check" class="w-5 h-5 mr-3"></i>
                Clientes
            </a>

            {{-- SECCIÓN: ADMINISTRACIÓN --}}
            @permission('settings')
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        Administración</p>
                </div>

                <a href="{{ route('roles.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('roles.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="shield-check" class="w-5 h-5 mr-3"></i>
                    Roles y Permisos
                </a>

                <a href="{{ route('staff.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('staff.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                    Personal
                </a>

                <a href="{{ route('settings.index') }}"
                    class="flex items-center px-4 py-3 {{ request()->routeIs('settings.index') ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:text-white dark:hover:text-white' }} rounded-lg group">
                    <i data-lucide="settings" class="w-5 h-5 mr-3"></i>
                    Configuración
                </a>
            @endpermission
        </nav>
    </div>
</div>
