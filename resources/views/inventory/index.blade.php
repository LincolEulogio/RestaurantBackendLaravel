<x-app-layout>
    <div x-data="inventoryManager()" class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inventario</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona tus insumos y stock</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('inventory.export.excel') }}"
                    class="h-11 px-5 rounded-xl border border-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 text-sm font-bold text-green-600 dark:text-green-400 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Excel
                </a>
                <a href="{{ route('inventory.export.pdf') }}"
                    class="h-11 px-5 rounded-xl border border-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 text-sm font-bold text-rose-600 dark:text-rose-400 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('inventory.print') }}" target="_blank"
                    class="h-11 px-5 rounded-xl border border-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 text-sm font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                        </path>
                    </svg>
                    Imprimir
                </a>
                <button @click="openCreateModal()"
                    class="h-11 px-5 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                        </path>
                    </svg>
                    Nuevo Insumo
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-8">

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Insumos -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1 dark:text-gray-200">Total Insumos</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-200" x-text="totalItems"></h3>
                    </div>
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <!-- Bajo Stock -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1 dark:text-gray-200">Bajo Stock</p>
                        <h3 class="text-3xl font-bold text-rose-600 dark:text-rose-200" x-text="lowStockCount"></h3>
                    </div>
                    <div class="p-3 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <!-- Valor Total -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1 dark:text-gray-200">Valor Total</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-200"
                            x-text="formatMoney(totalValue)"></h3>
                    </div>
                    <div
                        class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <!-- Categorias -->
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1 dark:text-gray-200">Categorías</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-200" x-text="uniqueCategories"></h3>
                    </div>
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Filters & Actions -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-80">
                        <input type="text" x-model="searchTerm" placeholder="Buscar insumos (Nombre, SKU)..."
                            class="w-full pl-10 pr-4 h-11 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition-all shadow-sm">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                    </div>

                    <select x-model="filterCategory" @change="currentPage = 1"
                        class="h-11 pl-4 pr-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-200 outline-none focus:border-blue-500 cursor-pointer shadow-sm hidden md:block">
                        <option>Todas las categorías</option>
                        <template x-for="cat in categoriesList" :key="cat">
                            <option x-text="cat"></option>
                        </template>
                    </select>

                    <select x-model="filterStatus" @change="currentPage = 1"
                        class="h-11 pl-4 pr-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-200 outline-none focus:border-blue-500 cursor-pointer shadow-sm hidden md:block">
                        <option>Todos los estados</option>
                        <option>OK</option>
                        <option>Bajo Stock</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-200">
                                    #
                                </th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-200">
                                    Insumo
                                </th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-200">
                                    Categoría
                                </th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 text-center dark:text-gray-200">
                                    Stock Actual</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 text-center dark:text-gray-200">
                                    Stock Mínimo</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 text-center dark:text-gray-200">
                                    Unidad</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 text-right dark:text-gray-200">
                                    Precio Unit.</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 text-center dark:text-gray-200">
                                    Estado</th>
                                <th
                                    class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 text-right dark:text-gray-200">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            <template x-for="item in paginatedItems" :key="item.id">
                                <tr class="hover:bg-gray-50/50 transition-colors dark:hover:bg-gray-700/50">
                                    <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-400 font-mono"
                                        x-text="item.id"></td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-500 dark:text-blue-400 flex items-center justify-center">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-gray-200 text-sm"
                                                    x-text="item.name"></p>
                                                <p class="text-xs text-gray-400" x-text="'SKU: ' + item.sku"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-600 dark:text-gray-200"
                                        x-text="item.category || '-'"></td>
                                    <td class="py-4 px-6 text-sm text-gray-900 dark:text-gray-200 font-bold text-center"
                                        x-text="Number(item.stock_current)"></td>
                                    <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-200 text-center"
                                        x-text="Number(item.stock_min)"></td>
                                    <td class="py-4 px-6 text-sm text-gray-500 dark:text-gray-200 text-center"
                                        x-text="item.unit"></td>
                                    <td class="py-4 px-6 text-sm font-bold text-gray-900 dark:text-gray-200 text-right"
                                        x-text="formatMoney(Number(item.price_unit))"></td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex py-1 px-3 rounded-full text-xs font-bold"
                                            :class="Number(item.stock_current) <= Number(item.stock_min) ?
                                                'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' :
                                                'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'"
                                            x-text="Number(item.stock_current) <= Number(item.stock_min) ? 'Bajo Stock' : 'OK'">
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button @click="editItem(item)"
                                                class="p-1.5 text-gray-400 dark:text-gray-200 hover:text-blue-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </button>
                                            <button @click="deleteItem(item.id)"
                                                class="p-1.5 text-gray-400 dark:text-gray-200 hover:text-rose-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr x-show="filteredItems.length === 0">
                                <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-200">
                                    No se encontraron insumos.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div x-show="totalPages > 1" class="flex justify-center mt-6">
            <nav class="flex items-center gap-2" aria-label="Pagination">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>

                <div class="flex items-center gap-1">
                    <template x-for="page in totalPages" :key="page">
                        <button @click="changePage(page)"
                            class="w-10 h-10 rounded-lg text-sm font-medium transition-colors"
                            :class="currentPage === page ?
                                'bg-blue-600 text-white shadow-md' :
                                'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'"
                            x-text="page">
                        </button>
                    </template>
                </div>

                <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                    class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            </nav>
        </div>

        <!-- Modal for Create/Edit -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div @click="closeModal()" class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity">
            </div>
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-700">
                    <form @submit.prevent="saveItem">
                        <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-200 mb-4"
                                x-text="isEdit ? 'Editar Insumo' : 'Nuevo Insumo'"></h3>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre</label>
                                        <input type="text" x-model="form.name" required
                                            class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">SKU</label>
                                        <input type="text" x-model="form.sku" required
                                            class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Categoría
                                        de
                                        Insumo:</label>
                                    <input type="text" x-model="form.category" placeholder="Ej: Carnes"
                                        class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Stock
                                            Actual</label>
                                        <input type="number" step="0.01" x-model="form.stock_current" required
                                            class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Stock
                                            Mínimo</label>
                                        <input type="number" step="0.01" x-model="form.stock_min" required
                                            class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Unidad</label>
                                        <select x-model="form.unit"
                                            class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                            <option value="unid">Unidad (unid)</option>
                                            <option value="kg">Kilogramo (kg)</option>
                                            <option value="lt">Litro (lt)</option>
                                            <option value="gr">Gramo (gr)</option>
                                            <option value="ml">Mililitro (ml)</option>
                                            <option value="oz">Onza (oz)</option>
                                            <option value="lb">Libra (lb)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Precio
                                            Unit.</label>
                                        <input type="number" step="0.01" x-model="form.price_unit" required
                                            class="w-full rounded-xl bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3 border-t border-gray-100 dark:border-gray-700">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-xl bg-blue-600 dark:bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                            <button @click="closeModal()" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-700 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    </div>
</x-app-layout>
