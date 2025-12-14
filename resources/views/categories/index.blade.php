<x-app-layout>
    <div x-data="categoryManager()" class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Categorías</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Administra las categorías del menú</p>
            </div>
            <button @click="openCreateModal()"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Nueva Categoría
            </button>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Categories Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-200 mb-1">Total Categorías</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white" x-text="categories.length">0</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Categories Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-200 mb-1">Activas</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-white"
                            x-text="categories.filter(c => c.is_active).length">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Inactive Categories Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-200 mb-1">Inactivas</p>
                        <p class="text-3xl font-bold text-gray-600 dark:text-white"
                            x-text="categories.filter(c => !c.is_active).length">0</p>
                    </div>
                    <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Most Used Category Card -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-200 mb-1">Más Usada</p>
                        <p class="text-lg font-bold text-blue-600 dark:text-white truncate"
                            x-text="categories.length > 0 ? categories[0].name : 'N/A'">
                            N/A</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories List -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-700 dark:text-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">#</th>
                            <th scope="col" class="px-6 py-4 font-bold">Nombre</th>
                            <th scope="col" class="px-6 py-4 font-bold">Descripción</th>
                            <th scope="col" class="px-6 py-4 font-bold">Slug</th>
                            <th scope="col" class="px-6 py-4 font-bold">Estado</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="category in paginatedCategories" :key="category.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-500 dark:text-gray-400" x-text="category.id">
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-200"
                                    x-text="category.name"></td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-200"
                                    x-text="category.description || '-'"></td>
                                <td class="px-6 py-4 font-mono text-xs text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 rounded-md inline-block my-3 mx-6"
                                    x-text="category.slug"></td>
                                <td class="px-6 py-4">
                                    <span
                                        :class="category.is_active ?
                                            'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' :
                                            'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'"
                                        class="px-2.5 py-1 rounded-full text-xs font-bold"
                                        x-text="category.is_active ? 'Activo' : 'Inactivo'">
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="editCategory(category)"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-200 dark:hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button @click="deleteCategory(category.id)"
                                            class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:text-rose-200 dark:hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="categories.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-200">
                                No hay categorías registradas.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Controls -->
        <div x-show="totalPages > 1" class="flex justify-center mt-6">
            <nav class="flex items-center gap-2" aria-label="Pagination">
                <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                    class="p-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
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
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </nav>
        </div>

        <!-- Create/Edit Modal -->
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div @click="closeModal()" class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-700">
                    <form @submit.prevent="saveCategory">
                        <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4"
                                x-text="isEdit ? 'Editar Categoría' : 'Nueva Categoría'"></h3>

                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre</label>
                                    <input type="text" x-model="form.name" @input="!isEdit && generateSlug()"
                                        required
                                        class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"
                                        placeholder="Ej: Entradas">
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Slug</label>
                                    <input type="text" x-model="form.slug" required
                                        class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"
                                        placeholder="ej-entradas">
                                    <p class="text-xs text-gray-500 mt-1 dark:text-gray-200">Identificador único para
                                        URL (automático).
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción</label>
                                    <textarea rows="3" x-model="form.description"
                                        class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"
                                        placeholder="Descripción opcional..."></textarea>
                                </div>

                                <div class="flex items-center">
                                    <input id="is_active" type="checkbox" x-model="form.is_active"
                                        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500">
                                    <label for="is_active"
                                        class="ml-2 block text-sm text-gray-900 dark:text-gray-200">Activo</label>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3 border-t border-gray-100 dark:border-gray-700">
                            <button type="submit"
                                class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                            <button @click="closeModal()" type="button"
                                class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-700 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
