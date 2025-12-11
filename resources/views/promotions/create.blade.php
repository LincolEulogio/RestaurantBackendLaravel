<x-app-layout>
    <div class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nueva Promoción</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Crea una nueva oferta para tus clientes</p>
            </div>
            <a href="{{ route('promotions.index') }}"
                class="bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver
            </a>
        </div>

        <!-- Form Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 sm:p-8">
            <form action="{{ route('promotions.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Title -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="title"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Título de la
                            Promoción</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"
                            placeholder="Ej: 2x1 en Pizzas Familiares">
                        @error('title')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Badge Label -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="badge_label"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Etiqueta
                            (Badge)</label>
                        <select name="badge_label" id="badge_label"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            <option value="">Ninguna</option>
                            <option value="Popular" {{ old('badge_label') == 'Popular' ? 'selected' : '' }}>Popular
                            </option>
                            <option value="Nuevo" {{ old('badge_label') == 'Nuevo' ? 'selected' : '' }}>Nuevo</option>
                            <option value="Limitado" {{ old('badge_label') == 'Limitado' ? 'selected' : '' }}>Limitado
                            </option>
                            <option value="Oferta" {{ old('badge_label') == 'Oferta' ? 'selected' : '' }}>Oferta
                            </option>
                        </select>
                        @error('badge_label')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="col-span-2">
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción</label>
                        <textarea name="description" id="description" rows="3" required
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"
                            placeholder="Detalla las condiciones de la promoción...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Percent -->
                    <div>
                        <label for="discount_percent"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Porcentaje de
                            Descuento (%)</label>
                        <input type="number" name="discount_percent" id="discount_percent"
                            value="{{ old('discount_percent') }}" min="0" max="100"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"
                            placeholder="Ej: 50">
                        @error('discount_percent')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="flex items-center h-full pt-6">
                        <input type="checkbox" name="status" id="status" value="1"
                            {{ old('status', '1') ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                        <label for="status" class="ml-2 block text-sm text-gray-900 dark:text-gray-200">Promoción
                            Activa</label>
                    </div>

                    <!-- Dates -->
                    <div>
                        <label for="start_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Fecha Inicio</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        @error('start_date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Fecha Fin</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                            class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        @error('end_date')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Products Section -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-3">Productos
                            Incluidos (Combos)</label>

                        <div id="products-container" class="space-y-3">
                            <!-- Rows will be added here -->
                        </div>

                        <button type="button" id="add-product-btn"
                            class="mt-3 text-sm text-blue-600 dark:text-blue-400 font-medium hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Producto
                        </button>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Imagen de
                            Referencia</label>
                        <div
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="image"
                                        class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Sube un archivo</span>
                                        <input id="image" name="image" type="file" class="sr-only"
                                            accept="image/*">
                                    </label>
                                    <p class="pl-1">o arrastra y suelta</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF hasta 2MB</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('products-container');
                        const addBtn = document.getElementById('add-product-btn');

                        // Available products from backend
                        const products = @json($products);

                        function createRow() {
                            const row = document.createElement('div');
                            row.className = 'flex items-center gap-2 product-row';

                            let options = '<option value="">Seleccionar producto...</option>';
                            products.forEach(p => {
                                options +=
                                    `<option value="${p.id}">${p.name} - S/ ${parseFloat(p.price).toFixed(2)}</option>`;
                            });

                            row.innerHTML = `
                            <select name="products[]" class="flex-1 rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white" required>
                                ${options}
                            </select>
                            <button type="button" class="remove-row p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        `;

                            // Add remove event
                            row.querySelector('.remove-row').addEventListener('click', function() {
                                row.remove();
                            });

                            container.appendChild(row);
                        }

                        addBtn.addEventListener('click', createRow);

                        // Add one initial row
                        createRow();
                    });
                </script>

                <div class="flex justify-end pt-5">
                    <button type="submit"
                        class="bg-blue-600 text-white px-8 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                        Guardar Promoción
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
