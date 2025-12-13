<x-app-layout>
    <div class="space-y-6" x-data="rolesManager()">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Roles y Permisos</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">Gestiona los roles de usuario y sus permisos en el
                    sistema</p>
            </div>
            <button @click="resetForm()"
                class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg flex items-center transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Rol
            </button>
        </div>

        <!-- Roles Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs text-center border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 font-semibold text-left w-64">Rol</th>
                            <th class="px-6 py-4 font-semibold w-24">Usuarios</th>
                            <th class="px-4 py-4 font-semibold">Dashboard</th>
                            <th class="px-4 py-4 font-semibold">Órdenes</th>
                            <th class="px-4 py-4 font-semibold">Mesas</th>
                            <th class="px-4 py-4 font-semibold">Blogs</th>
                            <th class="px-4 py-4 font-semibold">Cocina</th>
                            <th class="px-4 py-4 font-semibold">Menú</th>
                            <th class="px-4 py-4 font-semibold">Inventario</th>
                            <th class="px-4 py-4 font-semibold">Reportes</th>
                            <th class="px-4 py-4 font-semibold">Reservas</th>
                            <th class="px-4 py-4 font-semibold">Facturación</th>
                            <th class="px-4 py-4 font-semibold">Configuración</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                x-data="roleRow({{ $role->id }}, {{ json_encode($role->permissions ?? []) }}, '{{ $role->slug }}')">
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="p-2 
                                        {{ $role->slug == 'admin' ? 'bg-red-100 text-red-600' : '' }}
                                        {{ $role->slug == 'manager' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $role->slug == 'chef' ? 'bg-orange-100 text-orange-600' : '' }}
                                        {{ $role->slug == 'waiter' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $role->slug == 'cashier' ? 'bg-purple-100 text-purple-600' : '' }}
                                        {{ !in_array($role->slug, ['admin', 'manager', 'chef', 'waiter', 'cashier']) ? 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300' : '' }}
                                        rounded-lg mt-0.5">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                @if ($role->slug == 'admin')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                @elseif($role->slug == 'chef')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                @elseif($role->slug == 'waiter')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                @elseif($role->slug == 'cashier')
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                @endif
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $role->name }}</h3>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">
                                                {{ $role->slug }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="font-bold text-gray-900 dark:text-white block">{{ $role->user_count }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">usuarios</span>
                                </td>

                                <!-- Permissions Toggles -->
                                <!-- Dashboard -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('dashboard')"
                                        :class="(permissions.dashboard ?? false) ? 'bg-green-500' :
                                        'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.dashboard ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.dashboard ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Orders -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('orders')"
                                        :class="(permissions.orders ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.orders ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.orders ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Mesas (Tables) -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('tables')"
                                        :class="(permissions.tables ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.tables ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.tables ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Blogs -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('blogs')"
                                        :class="(permissions.blogs ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.blogs ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.blogs ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Kitchen -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('kitchen')"
                                        :class="(permissions.kitchen ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.kitchen ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.kitchen ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Menu -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('menu')"
                                        :class="(permissions.menu ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.menu ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.menu ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Inventory -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('inventory')"
                                        :class="(permissions.inventory ?? false) ? 'bg-green-500' :
                                        'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.inventory ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.inventory ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Reports -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('reports')"
                                        :class="(permissions.reports ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.reports ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.reports ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Reservations -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('reservations')"
                                        :class="(permissions.reservations ?? false) ? 'bg-green-500' :
                                        'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.reservations ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.reservations ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Billing -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('billing')"
                                        :class="(permissions.billing ?? false) ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.billing ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.billing ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>
                                <!-- Settings -->
                                <td class="px-4 py-4 text-center">
                                    <div @click="togglePermission('settings')"
                                        :class="(permissions.settings ?? false) ? 'bg-green-500' :
                                        'bg-gray-200 dark:bg-gray-600'"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        role="switch" :aria-checked="permissions.settings ?? false">
                                        <span class="sr-only">Toggle</span>
                                        <span aria-hidden="true"
                                            :class="(permissions.settings ?? false) ? 'translate-x-5' : 'translate-x-0'"
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out">
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Update Permissions Button -->
                                        <button @click="savePermissions()" :disabled="!hasChanges"
                                            :class="hasChanges ? 'bg-blue-600 hover:bg-blue-700' :
                                                'bg-gray-300 dark:bg-gray-600 cursor-not-allowed'"
                                            class="text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span x-show="hasChanges">Actualizar</span>
                                            <span x-show="!hasChanges">Sin cambios</span>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-gray-400 hover:text-red-600 transition-colors">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Description Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Descripción de Permisos</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dashboard -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Dashboard</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Acceso al panel principal y métricas</p>
                    </div>
                </div>
                <!-- Orders -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-orange-100 rounded-lg text-orange-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Órdenes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de pedidos y órdenes</p>
                    </div>
                </div>
                <!-- Tables -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-pink-100 rounded-lg text-pink-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m8-2a2 2 0 100-4m0 4a2 2 0 110-4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Mesas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de mesas y distribución</p>
                    </div>
                </div>
                <!-- Blogs -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Blogs</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de publicaciones del blog</p>
                    </div>
                </div>
                <!-- Menu -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-green-100 rounded-lg text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Menú</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Edición de productos y categorías</p>
                    </div>
                </div>
                <!-- Inventario -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-purple-100 rounded-lg text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Inventario</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Control de stock y materias primas</p>
                    </div>
                </div>
                <!-- Reportes -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Reportes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Análisis y reportes financieros</p>
                    </div>
                </div>
                <!-- Reservations -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-pink-100 rounded-lg text-pink-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Reservas</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de reservaciones de mesas</p>
                    </div>
                </div>
                <!-- Kitchen/KDS -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Cocina (KDS)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sistema de visualización de pedidos en
                            cocina</p>
                    </div>
                </div>
                <!-- Billing -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-teal-100 rounded-lg text-teal-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Caja y Facturación</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gestión de pagos, facturas y cierre de caja
                        </p>
                    </div>
                </div>
                <!-- Configuración -->
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Configuración</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ajustes del sistema y administración</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Create Role -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" @click="openModal = false"></div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:w-full sm:max-w-lg">
                    <form action="{{ route('roles.store') }}" method="POST">
                        @csrf

                        <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white mb-4">Crear Nuevo Rol
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre
                                        del Rol</label>
                                    <input type="text" name="name" x-model="currentRole.name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug
                                        (Código)</label>
                                    <input type="text" name="slug" x-model="currentRole.slug" required
                                        placeholder="ej: supervisor"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Permisos
                                        Iniciales</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[dashboard]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Dashboard</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[orders]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Órdenes</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[tables]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Mesas</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[blogs]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Blogs</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[menu]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Menú</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[inventory]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Inventario</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[kitchen]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Cocina</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[reports]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Reportes</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[reservations]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Reservas</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[billing]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Facturación</span></label>
                                        <label class="inline-flex items-center"><input type="checkbox"
                                                name="permissions[settings]" value="1"
                                                class="rounded border-gray-300 dark:border-gray-500 dark:bg-gray-700 text-green-600 shadow-sm focus:ring-green-500">
                                            <span
                                                class="ml-2 text-sm text-gray-600 dark:text-gray-300">Configuración</span></label>
                                    </div>
                                </div>
                                <div
                                    class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Después de crear el rol, puedes modificar los permisos usando los interruptores
                                        en la tabla.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto">Guardar</button>
                            <button type="button" @click="openModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
