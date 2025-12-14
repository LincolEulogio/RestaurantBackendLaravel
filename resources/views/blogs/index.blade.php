<x-app-layout>
    <div x-data="blogManager" class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Blogs</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-lg">Gestiona las publicaciones y noticias</p>
            </div>
            <button @click="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-xl flex items-center transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                Nueva Publicación
            </button>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total -->
            <div
                class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Publicaciones</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="totalStats.total">0</p>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                    <i data-lucide="file-text" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- Published -->
            <div
                class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Publicados</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="totalStats.published">0</p>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400">
                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                </div>
            </div>

            <!-- Drafts -->
            <div
                class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Borradores</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white" x-text="totalStats.draft">0</p>
                </div>
                <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-orange-600 dark:text-orange-400">
                    <i data-lucide="file-edit" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-2">
            <button @click="setFilter('all')"
                :class="currentFilter === 'all' ? 'bg-blue-600 text-white shadow-md' :
                    'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                class="px-4 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap">
                Todos
            </button>
            <button @click="setFilter('published')"
                :class="currentFilter === 'published' ? 'bg-green-600 text-white shadow-md' :
                    'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                class="px-4 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-400"></span>
                Publicados
            </button>
            <button @click="setFilter('draft')"
                :class="currentFilter === 'draft' ? 'bg-orange-500 text-white shadow-md' :
                    'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                class="px-4 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-400"></span>
                Borradores
            </button>
        </div>

        <!-- Blogs Grid/Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="bg-gray-50/50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 font-semibold text-left">#</th>
                            <th class="px-6 py-4 font-semibold text-left">Contenido</th>
                            <th class="px-4 py-4 font-semibold text-center">Estado</th>
                            <th class="px-4 py-4 font-semibold text-center">Fecha</th>
                            <th class="px-6 py-4 font-semibold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="blog in paginatedBlogs" :key="blog.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 font-medium" x-text="blog.id">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="relative h-16 w-24 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-600">
                                            <template x-if="blog.image_url">
                                                <img :src="blog.image_url" :alt="blog.title"
                                                    class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!blog.image_url">
                                                <div class="flex items-center justify-center h-full">
                                                    <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                                </div>
                                            </template>
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white text-base mb-1"
                                                x-text="blog.title"></div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded w-fit"
                                                x-text="blog.slug"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span
                                        :class="blog.status === 'published' ?
                                            'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800' :
                                            'bg-orange-100 text-orange-700 border-orange-200 dark:bg-orange-900/30 dark:text-orange-400 dark:border-orange-800'"
                                        class="px-3 py-1 text-xs font-semibold rounded-full border inline-flex items-center gap-1.5 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                            :class="blog.status === 'published' ? 'bg-green-500' : 'bg-orange-500'"></span>
                                        <span x-text="blog.status === 'published' ? 'Publicado' : 'Borrador'"></span>
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <i data-lucide="calendar" class="w-4 h-4"></i>
                                        <span x-text="formatDate(blog.published_at || blog.created_at)"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div
                                        class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editBlog(blog)"
                                            class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                            title="Editar">
                                            <i data-lucide="pencil" class="w-5 h-5"></i>
                                        </button>
                                        <button @click="deleteBlog(blog.id)"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="Eliminar">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="filteredBlogs.length === 0">
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-full mb-3">
                                            <i data-lucide="file-x" class="w-8 h-8 text-gray-400"></i>
                                        </div>
                                        <p class="text-lg font-medium">No se encontraron publicaciones</p>
                                        <p class="text-sm mt-1">Intenta crear una nueva publicación</p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
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

        <!-- Create/Edit Modal -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

            <!-- Modal Panel -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-4xl transform transition-all"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <form @submit.prevent="saveBlog" class="flex flex-col max-h-[90vh]">
                        <!-- Modal Header -->
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white"
                                    x-text="isEdit ? 'Editar Publicación' : 'Nueva Publicación'"></h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Completa la información del
                                    blog</p>
                            </div>
                            <button type="button" @click="closeModal()"
                                class="text-gray-400 hover:text-gray-500 transition-colors">
                                <i data-lucide="x" class="w-6 h-6"></i>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-6 overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <!-- Left Column: Form -->
                                <div class="lg:col-span-2 space-y-5">
                                    <!-- Title & Slug -->
                                    <div class="space-y-4">
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Título</label>
                                            <input type="text" x-model="form.title" @input="generateSlug" required
                                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors text-lg font-medium placeholder:font-normal"
                                                placeholder="Ej: Los secretos de la cocina peruana...">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Slug
                                                (URL)</label>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-3 top-2.5 text-gray-400 text-sm font-mono">/blog/</span>
                                                <input type="text" x-model="form.slug" required
                                                    class="w-full pl-14 rounded-xl border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 font-mono text-sm">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Contenido</label>
                                        <div class="relative">
                                            <textarea x-model="form.content" rows="12" required
                                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none custom-scrollbar"
                                                placeholder="Escribe el contenido de tu publicación aquí..."></textarea>
                                            <div class="absolute bottom-3 right-3 text-xs text-gray-400">Markdown
                                                compatible</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Sidebar -->
                                <div class="space-y-6">
                                    <!-- Status Card -->
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Estado
                                            de Publicación</label>
                                        <div
                                            class="grid grid-cols-2 gap-2 p-1 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <button type="button" @click="form.status = 'draft'"
                                                :class="form.status === 'draft' ?
                                                    'bg-orange-100 text-orange-700 shadow-sm' :
                                                    'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                                class="px-3 py-2 rounded-md text-sm font-medium transition-all text-center">
                                                Borrador
                                            </button>
                                            <button type="button" @click="form.status = 'published'"
                                                :class="form.status === 'published' ? 'bg-green-100 text-green-700 shadow-sm' :
                                                    'text-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                                class="px-3 py-2 rounded-md text-sm font-medium transition-all text-center">
                                                Publicar
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Image Upload Card -->
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                                        <label
                                            class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Imagen
                                            Destacada</label>

                                        <!-- Image Preview -->
                                        <div
                                            class="relative w-full aspect-video rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-500 transition-colors group mb-3">
                                            <template
                                                x-if="form.image_preview || (isEdit && form.image_url && !form.image_preview)">
                                                <img :src="form.image_preview || form.image_url"
                                                    class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!form.image_preview && (!isEdit || !form.image_url)">
                                                <div
                                                    class="flex flex-col items-center justify-center h-full text-gray-400">
                                                    <i data-lucide="image-plus" class="w-8 h-8 mb-2"></i>
                                                    <span class="text-sm">Click para subir</span>
                                                </div>
                                            </template>

                                            <!-- Overlay -->
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                                @click="document.getElementById('blog-image').click()">
                                                <span class="text-white font-medium text-sm flex items-center gap-2">
                                                    <i data-lucide="upload" class="w-4 h-4"></i>
                                                    Cambiar Imagen
                                                </span>
                                            </div>
                                            <input type="file" id="blog-image" accept="image/*" class="hidden"
                                                @change="handleFileUpload">
                                        </div>
                                        <p class="text-xs text-center text-gray-500">Recomendado: 1200x630px · Máx 10MB
                                        </p>
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
                                <i data-lucide="save" class="w-4 h-4"></i>
                                <span x-text="isEdit ? 'Guardar Cambios' : 'Publicar'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Scrollbar Styles -->
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(107, 114, 128, 0.8);
        }
    </style>
</x-app-layout>
