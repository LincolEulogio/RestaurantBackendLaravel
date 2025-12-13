<x-app-layout>
    <div x-data="menuManager()" class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Menú Digital</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona los platillos, precios y disponibilidad</p>
            </div>
            <button @click="openCreateModal()"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 dark:hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nuevo Plato
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Dishes Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-100 mb-1">Total Platos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100" x-text="products.length">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Dishes Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-100 mb-1">Disponibles</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-100"
                            x-text="products.filter(p => p.is_available).length">0</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Categories Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-100 mb-1">Categorías</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-100" x-text="categories.length">0
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Average Price Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-100 mb-1">Precio Promedio</p>
                        <p class="text-3xl font-bold text-orange-600 dark:text-orange-100"
                            x-text="products.length > 0 ? formatMoney(products.reduce((sum, p) => sum + parseFloat(p.price), 0) / products.length) : 'S/ 0.00'">
                            S/ 0.00</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refined Filters & Search Bar -->
        <div
            class="flex flex-col md:flex-row gap-4 justify-between items-center bg-white dark:bg-gray-800 p-2 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <!-- Category Tabs (Pills) -->
            <div class="flex gap-2 overflow-x-auto pb-4 w-full p-4 scrollbar-thin">
                <button @click="setFilter('Todos')"
                    :class="currentFilter === 'Todos' ? 'bg-gray-900 text-white shadow-md' :
                        'text-gray-600 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900'"
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap">Todos</button>
                <template x-for="cat in categories" :key="cat.id">
                    <button @click="setFilter(cat.name)"
                        :class="currentFilter === cat.name ? 'bg-gray-900 text-white shadow-md' :
                            'text-gray-600 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap"
                        x-text="cat.name"></button>
                </template>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">

                <template x-for="product in filteredProducts" :key="product.id">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden group hover:shadow-md transition-all relative flex flex-col h-full">
                        <div class="h-48 bg-gray-200 dark:bg-gray-700 relative overflow-hidden shrink-0">
                            <!-- Image handling -->
                            <img :src="product.image_url"
                                :alt="product.name"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <!-- Availability Badge -->
                            <div class="absolute top-2 right-2">
                                <span :class="product.is_available ? 'text-green-700' : 'text-rose-600'"
                                    class="bg-white/80 dark:bg-gray-700 backdrop-blur-md text-[10px] font-bold px-2 py-1 rounded-full shadow-sm border border-white/50 dark:border-gray-700 flex items-center gap-1">
                                    <span :class="product.is_available ? 'bg-green-500' : 'bg-rose-500'"
                                        class="w-1.5 h-1.5 rounded-full"></span>
                                    <span x-text="product.is_available ? 'Disponible' : 'Agotado'"></span>
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <div class="mb-2">
                                <span
                                    class="text-[10px] font-bold text-blue-600 dark:text-blue-100 uppercase tracking-wide bg-blue-50 dark:bg-blue-700 px-2 py-0.5 rounded-full"
                                    x-text="product.category ? product.category.name : 'Sin Categoría'"></span>
                                <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base leading-tight mt-2"
                                    x-text="product.name">
                                </h3>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-4" x-text="product.description"></p>

                            <div
                                class="flex items-center justify-between mt-auto pt-3 border-t border-gray-50 dark:border-gray-700">
                                <span class="text-lg font-bold text-gray-900 dark:text-gray-100"
                                    x-text="formatMoney(product.price)"></span>
                                <div class="flex gap-1">
                                    <button @click="editProduct(product)"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-100 dark:hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button @click="deleteProduct(product.id)"
                                        class="p-1.5 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:text-rose-100 dark:hover:bg-rose-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <div x-show="filteredProducts.length === 0" class="col-span-full py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-100">No se encontraron productos.</p>
                </div>

            </div>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <!-- Backdrop -->
            <div @click="closeModal()" class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity">
            </div>

            <!-- Modal Content -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <form @submit.prevent="saveProduct">
                        <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="w-full">
                                    <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-gray-100 mb-6"
                                        id="modal-title" x-text="isEdit ? 'Editar Plato' : 'Nuevo Plato'"></h3>

                                    <div class="space-y-6">
                                        <!-- Image Upload Section -->
                                        <div>
                                            <div class="flex items-center justify-center w-full">
                                                <label for="dropzone-file"
                                                    class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <svg class="w-8 h-8 mb-3 text-gray-400" aria-hidden="true"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                        </svg>
                                                        <p class="mb-1 text-sm text-gray-500"><span
                                                                class="font-semibold">Click para subir</span> o
                                                            arrastra
                                                        </p>
                                                        <p class="text-xs text-gray-400">SVG, PNG, JPG (MAX. 800x400px)
                                                        </p>
                                                    </div>
                                                    <input id="dropzone-file" type="file" class="hidden"
                                                        name="image" accept="image/*" @change="handleFileUpload" />

                                                </label>
                                            </div>
                                            <!-- Image Preview Text -->
                                            <div x-show="form.image"
                                                class="mt-2 text-sm text-green-600 dark:text-green-100 text-center font-medium bg-green-50 dark:bg-green-700 py-1 rounded-lg">
                                                Imagen seleccionada: <span
                                                    x-text="form.image ? form.image.name : ''"></span>
                                            </div>
                                        </div>

                                        <!-- Form Inputs -->
                                        <div class="space-y-4">
                                            <div>
                                                <label
                                                    class="block text-sm font-bold text-gray-700 dark:text-gray-100 mb-1">Nombre
                                                    del
                                                    Plato</label>
                                                <input type="text" x-model="form.name" required
                                                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                                    placeholder="Ej: Lomo Saltado">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label
                                                        class="block text-sm font-bold text-gray-700 dark:text-gray-100 mb-1">Precio</label>
                                                    <div class="relative rounded-md shadow-sm">
                                                        <div
                                                            class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                            <span
                                                                class="text-gray-500 dark:text-gray-100 sm:text-sm">S/</span>
                                                        </div>
                                                        <input type="number" step="0.01" x-model="form.price"
                                                            required
                                                            class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-100 pl-7 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                                            placeholder="0.00">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-bold text-gray-700 dark:text-gray-100 mb-1">Categoría</label>
                                                    <select x-model="form.category_id" required
                                                        class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:ring-blue-500">
                                                        <option value="" disabled>Seleccionar</option>
                                                        <template x-for="cat in categories" :key="cat.id">
                                                            <option :value="cat.id" x-text="cat.name"></option>
                                                        </template>
                                                    </select>
                                                </div>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-bold text-gray-700 dark:text-gray-100 mb-1">Descripción</label>
                                                <textarea rows="3" x-model="form.description"
                                                    class="w-full rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-600 dark:text-gray-100 focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-500 dark:focus:ring-blue-500"
                                                    placeholder="Breve descripción..."></textarea>
                                            </div>

                                            <div class="flex items-center pt-2">
                                                <input id="is_available" type="checkbox" x-model="form.is_available"
                                                    class="h-4 w-4 rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500 dark:text-blue-600 dark:focus:ring-blue-500">
                                                <label for="is_available"
                                                    class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Disponible
                                                    para la
                                                    venta</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                                <button type="submit"
                                    class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                                <button @click="closeModal()" type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-700 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
