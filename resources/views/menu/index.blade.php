<x-app-layout>
    <div x-data="menuManager()" class="space-y-8">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Menú Digital</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-lg">Gestiona tu carta, precios y disponibilidad</p>
            </div>
            <button @click="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl flex items-center transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Plato
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Dishes -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Platos</p>
                        <h3 class="text-3xl font-bold text-gray-900 dark:text-white" x-text="products.length">0</h3>
                    </div>
                    <div
                        class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Available Dishes -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Disponibles</p>
                        <h3 class="text-3xl font-bold text-green-600 dark:text-green-400"
                            x-text="products.filter(p => p.is_available).length">0</h3>
                    </div>
                    <div
                        class="p-3 bg-green-50 dark:bg-green-900/30 rounded-xl text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Categorías</p>
                        <h3 class="text-3xl font-bold text-purple-600 dark:text-purple-400" x-text="categories.length">0
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-xl text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Avg Price -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Precio Promedio</p>
                        <h3 class="text-3xl font-bold text-orange-600 dark:text-orange-400"
                            x-text="products.length > 0 ? formatMoney(products.reduce((sum, p) => sum + parseFloat(p.price), 0) / products.length) : 'S/ 0.00'">
                            S/ 0.00</h3>
                    </div>
                    <div
                        class="p-3 bg-orange-50 dark:bg-orange-900/30 rounded-xl text-orange-600 dark:text-orange-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <button @click="setFilter('Todos')"
                :class="currentFilter === 'Todos' ? 'bg-gray-900 text-white shadow-md' :
                    'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                class="px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap">
                Todos
            </button>
            <template x-for="cat in categories" :key="cat.id">
                <button @click="setFilter(cat.name)"
                    :class="currentFilter === cat.name ? 'bg-gray-900 text-white shadow-md' :
                        'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                    class="px-5 py-2.5 rounded-full text-sm font-bold transition-all whitespace-nowrap"
                    x-text="cat.name"></button>
            </template>
        </div>

        <!-- Menu Grid -->
        <div class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <template x-for="product in paginatedProducts" :key="product.id">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-xl transition-all duration-300 relative flex flex-col h-full">

                        <!-- Image Container -->
                        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative overflow-hidden shrink-0">
                            <template x-if="product.image_url">
                                <img :src="product.image_url" :alt="product.name"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </template>
                            <template x-if="!product.image_url">
                                <div class="flex items-center justify-center h-full text-gray-300 dark:text-gray-600">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            </template>

                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span
                                    :class="product.is_available ? 'bg-green-500/90 text-white' : 'bg-rose-500/90 text-white'"
                                    class="text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg backdrop-blur-sm flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                    <span x-text="product.is_available ? 'DISPONIBLE' : 'AGOTADO'"></span>
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex flex-col flex-1">
                            <div class="mb-3">
                                <span
                                    class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-md"
                                    x-text="product.category ? product.category.name : 'Sin Categoría'"></span>
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight mt-2 line-clamp-2"
                                    x-text="product.name"></h3>
                            </div>

                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4 flex-1"
                                x-text="product.description || 'Sin descripción'"></p>

                            <div
                                class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                                <span class="text-xl font-bold text-gray-900 dark:text-white"
                                    x-text="formatMoney(product.price)"></span>
                                <div class="flex gap-2">
                                    <button @click="editProduct(product)"
                                        class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button @click="deleteProduct(product.id)"
                                        class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors"
                                        title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
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
                <div x-show="filteredProducts.length === 0" class="col-span-full py-16 text-center">
                    <div
                        class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">No hay productos</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">No se encontraron productos en esta categoría.</p>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div x-show="totalPages > 1" class="flex justify-center mt-8 pb-4">
                <nav class="flex items-center gap-2" aria-label="Pagination">
                    <!-- Previous Button -->
                    <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                        class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <!-- Page Numbers -->
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

                    <!-- Next Button -->
                    <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                        class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Create/Edit Modal (Modern Design) -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all text-left overflow-hidden"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <form @submit.prevent="saveProduct" class="flex flex-col max-h-[90vh]">
                        <!-- Modal Header -->
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white"
                                    x-text="isEdit ? 'Editar Plato' : 'Nuevo Plato'"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Información del producto del
                                    menú</p>
                            </div>
                            <button type="button" @click="closeModal()"
                                class="text-gray-400 hover:text-gray-500 transition-colors bg-white dark:bg-gray-700 p-2 rounded-lg hover:shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                <!-- Left Column: Images -->
                                <div class="space-y-4">
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Imagen del
                                        Plato</label>

                                    <!-- Image Preview Area -->
                                    <div class="relative w-full aspect-square bg-gray-100 dark:bg-gray-700 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group cursor-pointer"
                                        @click="document.getElementById('dropzone-file').click()">

                                        <!-- Actual Image -->
                                        <template x-if="form.image_preview || form.image_url">
                                            <img :src="form.image_preview || form.image_url"
                                                class="w-full h-full object-cover">
                                        </template>

                                        <!-- Placeholder -->
                                        <template x-if="!form.image_preview && !form.image_url">
                                            <div
                                                class="flex flex-col items-center justify-center h-full text-gray-400 p-4 text-center">
                                                <div class="bg-white dark:bg-gray-800 p-4 rounded-full shadow-sm mb-3">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <p class="font-medium text-gray-600 dark:text-gray-300">Click para
                                                    subir imagen</p>
                                                <p class="text-xs text-gray-500 mt-1">Recomendado: 800x800px</p>
                                            </div>
                                        </template>

                                        <!-- Overlay on Hover -->
                                        <div
                                            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span
                                                class="bg-white text-gray-900 px-4 py-2 rounded-lg font-medium text-sm shadow-sm flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                                Cambiar Imagen
                                            </span>
                                        </div>
                                    </div>

                                    <input id="dropzone-file" type="file" class="hidden" name="image"
                                        accept="image/*" @change="handleFileUpload" />

                                    <div class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 shrink-0"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-xs text-blue-800 dark:text-blue-200">
                                            Asegúrate de que la imagen sea de alta calidad y centrada. Formatos: JPG,
                                            PNG, WEBP.
                                        </p>
                                    </div>
                                </div>

                                <!-- Right Column: Inputs -->
                                <div class="space-y-5">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nombre
                                            del Plato</label>
                                        <input type="text" x-model="form.name" required
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4"
                                            placeholder="Ej: Lomo Saltado">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Precio</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-4 top-2.5 text-gray-500 dark:text-gray-400 font-bold">S/</span>
                                                <input type="number" step="0.01" x-model="form.price" required
                                                    class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 pl-10 py-2.5"
                                                    placeholder="0.00">
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Categoría</label>
                                            <select x-model="form.category_id" required
                                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4">
                                                <option value="" disabled>Seleccionar</option>
                                                <template x-for="cat in categories" :key="cat.id">
                                                    <option :value="cat.id" x-text="cat.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Descripción</label>
                                        <textarea rows="4" x-model="form.description"
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 px-4 py-3 resize-none"
                                            placeholder="Describe los ingredientes y detalles del plato..."></textarea>
                                    </div>

                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl border border-gray-100 dark:border-gray-600">
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center gap-3 cursor-pointer">
                                                <div class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" x-model="form.is_available"
                                                        class="sr-only peer">
                                                    <div
                                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                    </div>
                                                </div>
                                                <span
                                                    class="text-sm font-bold text-gray-700 dark:text-gray-300">Disponible
                                                    para venta</span>
                                            </label>
                                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full"
                                                :class="form.is_available ? 'bg-green-100 text-green-700' :
                                                    'bg-red-100 text-red-700'"
                                                x-text="form.is_available ? 'ACTIVO' : 'INACTIVO'">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div
                            class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" @click="closeModal()"
                                class="px-5 py-2.5 rounded-xl text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Guardar Plato</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
