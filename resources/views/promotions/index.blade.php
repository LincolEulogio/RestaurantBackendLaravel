<x-app-layout>
    <div x-data="promotionsManager" class="space-y-8">

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Promociones</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-lg">Gestiona ofertas especiales y campañas</p>
            </div>
            <button @click="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl flex items-center transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Promoción
            </button>
        </div>

        <!-- Filters & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Active Promotions -->
            <div @click="currentFilter = 'active'" :class="currentFilter === 'active' ? 'ring-2 ring-blue-500' : ''"
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm cursor-pointer hover:shadow-md transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Activas</p>
                        <h3 class="text-3xl font-bold text-green-600 dark:text-green-400"
                            x-text="promotions.filter(p => p.status).length">0</h3>
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

            <!-- Inactive Promotions -->
            <div @click="currentFilter = 'inactive'" :class="currentFilter === 'inactive' ? 'ring-2 ring-blue-500' : ''"
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm cursor-pointer hover:shadow-md transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Inactivas</p>
                        <h3 class="text-3xl font-bold text-gray-400 dark:text-gray-500"
                            x-text="promotions.filter(p => !p.status).length">0</h3>
                    </div>
                    <div
                        class="p-3 bg-gray-50 dark:bg-gray-700 rounded-xl text-gray-400 dark:text-gray-300 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div @click="currentFilter = 'all'" :class="currentFilter === 'all' ? 'ring-2 ring-blue-500' : ''"
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm cursor-pointer hover:shadow-md transition-all group">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total</p>
                        <h3 class="text-3xl font-bold text-blue-600 dark:text-blue-400" x-text="promotions.length">0
                        </h3>
                    </div>
                    <div
                        class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promotions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="promo in filteredPromotions" :key="promo.id">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 group hover:shadow-xl transition-all duration-300 flex flex-col h-full relative">

                    <!-- Image Area -->
                    <div class="h-48 bg-gray-100 dark:bg-gray-700 relative overflow-hidden shrink-0">
                        <template x-if="promo.image_url">
                            <img :src="promo.image_url" :alt="promo.title"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </template>
                        <template x-if="!promo.image_url">
                            <div
                                class="flex items-center justify-center h-full text-gray-300 dark:text-gray-600 bg-gray-50 dark:bg-gray-700 pattern-dots">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                                    </path>
                                </svg>
                            </div>
                        </template>

                        <!-- Discount Badge -->
                        <template x-if="promo.discount_percent">
                            <div class="absolute top-3 right-3">
                                <span
                                    class="bg-red-600 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg transform rotate-3 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                    <span x-text="promo.discount_percent + '% OFF'"></span>
                                </span>
                            </div>
                        </template>

                        <!-- Status Badge -->
                        <div class="absolute top-3 left-3">
                            <span :class="promo.status ? 'bg-green-500/90 text-white' : 'bg-gray-500/90 text-white'"
                                class="text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg backdrop-blur-sm flex items-center gap-1.5 uppercase tracking-wide">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                <span x-text="promo.status ? 'ACTIVO' : 'INACTIVO'"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-1">
                        <div class="mb-3">
                            <template x-if="promo.badge_label">
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md mb-2 inline-block"
                                    :class="{
                                        'bg-orange-100 text-orange-700': promo.badge_label === 'Popular',
                                        'bg-green-100 text-green-700': promo.badge_label === 'Nuevo',
                                        'bg-blue-100 text-blue-700': promo.badge_label === 'Limitado',
                                        'bg-red-100 text-red-700': promo.badge_label === 'Oferta'
                                    }"
                                    x-text="promo.badge_label"></span>
                            </template>
                            <h3 class="font-bold text-gray-900 dark:text-white text-xl leading-tight"
                                x-text="promo.title"></h3>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 mb-4 flex-1"
                            x-text="promo.description"></p>

                        <!-- Dates -->
                        <div
                            class="text-xs text-gray-400 dark:text-gray-500 mb-4 font-mono bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                            <div class="flex justify-between">
                                <span>Desde: <span class="text-gray-600 dark:text-gray-300"
                                        x-text="formatDate(promo.start_date)"></span></span>
                                <span>Hasta: <span class="text-gray-600 dark:text-gray-300"
                                        x-text="formatDate(promo.end_date)"></span></span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                            <div class="flex -space-x-2 overflow-hidden">
                                <template x-for="(prod, index) in promo.products.slice(0,3)" :key="prod.id">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center overflow-hidden"
                                        :title="prod.name">
                                        <template x-if="prod.image_url">
                                            <img :src="prod.image_url" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!prod.image_url">
                                            <span class="text-[8px] font-bold"
                                                x-text="prod.name.substring(0,2)"></span>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="promo.products.length > 3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 border-2 border-white dark:border-gray-800 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                        <span x-text="'+' + (promo.products.length - 3)"></span>
                                    </div>
                                </template>
                            </div>

                            <div class="flex gap-2">
                                <button @click="editPromotion(promo)"
                                    class="p-2 rounded-lg text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
                                    title="Editar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button @click="deletePromotion(promo.id)"
                                    class="p-2 rounded-lg text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/30 transition-colors"
                                    title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div x-show="filteredPromotions.length === 0" class="col-span-full py-16 text-center">
                <div
                    class="w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">No hay promociones</h3>
                <p class="mt-1 text-gray-500 dark:text-gray-400">Crea una nueva promoción para verla aquí.</p>
            </div>
        </div>

        <!-- Modern Modal -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-4xl transform transition-all text-left overflow-hidden">
                    <form @submit.prevent="savePromotion" class="flex flex-col max-h-[90vh]">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white"
                                    x-text="isEdit ? 'Editar Promoción' : 'Nueva Promoción'"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Configura los detalles de la
                                    oferta</p>
                            </div>
                            <button type="button" @click="closeModal()"
                                class="text-gray-400 hover:text-gray-500 bg-white dark:bg-gray-700 p-2 rounded-lg hover:shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                                <!-- Left: Image & Badge -->
                                <div class="space-y-6">
                                    <!-- Image Upload -->
                                    <div class="space-y-3">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Imagen
                                            Promocional</label>
                                        <div class="relative w-full aspect-square bg-gray-100 dark:bg-gray-700 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-500 transition-colors group cursor-pointer"
                                            @click="document.getElementById('promotion-image').click()">

                                            <template x-if="form.image_preview || form.image_url">
                                                <img :src="form.image_preview || form.image_url"
                                                    class="w-full h-full object-cover">
                                            </template>

                                            <template x-if="!form.image_preview && !form.image_url">
                                                <div
                                                    class="flex flex-col items-center justify-center h-full text-gray-400 p-4 text-center">
                                                    <div
                                                        class="bg-white dark:bg-gray-800 p-3 rounded-full shadow-sm mb-2">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm">Subir Imagen</span>
                                                </div>
                                            </template>

                                            <!-- Overlay -->
                                            <div
                                                class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                <span
                                                    class="bg-white text-gray-900 px-3 py-1.5 rounded-lg text-sm font-medium shadow-sm">Cambiar</span>
                                            </div>
                                        </div>
                                        <input type="file" id="promotion-image" class="hidden" accept="image/*"
                                            @change="handleFileUpload">
                                    </div>

                                    <!-- Badge & Discount -->
                                    <div class="space-y-4 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase mb-1">Etiqueta
                                                (Badge)</label>
                                            <div class="flex flex-wrap gap-2">
                                                <template x-for="badge in ['Popular', 'Nuevo', 'Limitado', 'Oferta']">
                                                    <button type="button" @click="form.badge_label = badge"
                                                        :class="form.badge_label === badge ?
                                                            'bg-blue-600 text-white shadow-md' :
                                                            'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200'"
                                                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all border border-transparent"
                                                        x-text="badge"></button>
                                                </template>
                                            </div>
                                        </div>
                                        <div>
                                            <label
                                                class="block text-xs font-bold text-gray-500 uppercase mb-1">Descuento
                                                (%)</label>
                                            <div class="relative">
                                                <input type="number" x-model="form.discount_percent" min="0"
                                                    max="100"
                                                    class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 pr-8"
                                                    placeholder="0">
                                                <span class="absolute right-3 top-2.5 text-gray-400 font-bold">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Center/Right: Details -->
                                <div class="lg:col-span-2 space-y-5">
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Título
                                            de la Promoción</label>
                                        <input type="text" x-model="form.title" required
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 py-2.5 px-4 font-medium"
                                            placeholder="Ej: 2x1 en Pizzas Familiares">
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Descripción</label>
                                        <textarea rows="3" x-model="form.description" required
                                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 resize-none"
                                            placeholder="Condiciones y detalles de la promoción..."></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Fecha
                                                Inicio</label>
                                            <input type="date" x-model="form.start_date"
                                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">Fecha
                                                Fin</label>
                                            <input type="date" x-model="form.end_date"
                                                class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>

                                    <!-- Product Selection (Checkbox List) -->
                                    <div>
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Productos
                                            Incluidos</label>
                                        <div
                                            class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl max-h-48 overflow-y-auto p-2 custom-scrollbar">
                                            <template x-for="product in availableProducts" :key="product.id">
                                                <label
                                                    class="flex items-center gap-3 p-2 hover:bg-white dark:hover:bg-gray-600 rounded-lg cursor-pointer transition-colors">
                                                    <input type="checkbox" :value="product.id"
                                                        x-model="form.products"
                                                        class="rounded text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-500">
                                                    <div class="flex items-center gap-3 flex-1">
                                                        <div
                                                            class="w-8 h-8 rounded-md bg-gray-200 dark:bg-gray-500 overflow-hidden shrink-0">
                                                            <template x-if="product.image_url">
                                                                <img :src="product.image_url"
                                                                    class="w-full h-full object-cover">
                                                            </template>
                                                        </div>
                                                        <div class="text-sm">
                                                            <p class="font-bold text-gray-900 dark:text-gray-100"
                                                                x-text="product.name"></p>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400"
                                                                x-text="'S/ ' + parseFloat(product.price).toFixed(2)">
                                                            </p>
                                                        </div>
                                                    </div>
                                                </label>
                                            </template>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2 text-right"
                                            x-text="form.products.length + ' productos seleccionados'"></p>
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <div class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" x-model="form.status" class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">Promoción
                                                Activa</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
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
                                <span>Guardar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
