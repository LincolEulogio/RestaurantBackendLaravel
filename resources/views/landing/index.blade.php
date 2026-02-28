<x-app-layout>
    <div x-data="landingManager()" class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Página de Inicio</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Personaliza el contenido de tu landing page</p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex flex-wrap gap-2 p-1 bg-gray-100 dark:bg-gray-800/50 rounded-xl w-fit">
            <button @click="activeTab = 'about'" :class="activeTab === 'about' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all">
                Sobre Nosotros
            </button>
            <button @click="activeTab = 'values'" :class="activeTab === 'values' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all">
                Valores
            </button>
            <button @click="activeTab = 'testimonials'" :class="activeTab === 'testimonials' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all">
                Testimonios
            </button>
            <button @click="activeTab = 'gallery'" :class="activeTab === 'gallery' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all">
                Galería
            </button>
        </div>

        <!-- Tab Content: Sobre Nosotros -->
        <div x-show="activeTab === 'about'" x-transition class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('landing-page.update-general') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <!-- HERO SECTION -->
                <div class="border-b border-gray-100 dark:border-gray-700 pb-6 mb-6">
                    <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="layout" class="w-4 h-4 text-blue-600"></i>
                        Sección Hero (Banner Principal)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Título Hero</label>
                                <input type="text" name="landing_hero_title" value="{{ $hero_title }}" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Subtítulo Hero</label>
                                <textarea name="landing_hero_subtitle" rows="2" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">{{ $hero_subtitle }}</textarea>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Imagen de Fondo (Hero)</label>
                            <div class="flex items-center gap-4">
                                @if($hero_image)
                                    <img src="{{ $hero_image }}" class="w-32 h-20 object-cover rounded-xl border border-gray-100" />
                                @endif
                                <input type="file" name="landing_hero_image" class="block w-full text-xs text-gray-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ABOUT US SECTION -->
                <h3 class="text-md font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                    Sección Sobre Nosotros
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Título de la Sección</label>
                            <input type="text" name="landing_about_title" value="{{ $about_title }}" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción 1</label>
                            <textarea name="landing_about_description1" rows="3" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">{{ $about_description1 }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción 2</label>
                            <textarea name="landing_about_description2" rows="3" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">{{ $about_description2 }}</textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Años Experiencia</label>
                                <input type="text" name="landing_about_years" value="{{ $about_years }}" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre del Chef</label>
                                <input type="text" name="landing_chef_name" value="{{ $chef_name }}" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Frase del Chef</label>
                            <input type="text" name="landing_chef_quote" value="{{ $chef_quote }}" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Imagen Principal</label>
                            <div class="flex items-center gap-4">
                                @if($about_image)
                                    <img src="{{ $about_image }}" class="w-20 h-20 object-cover rounded-xl border border-gray-100" />
                                @endif
                                <input type="file" name="landing_about_image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:text-gray-300">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        <!-- Tab Content: Valores -->
        <div x-show="activeTab === 'values'" x-transition class="space-y-4">
            <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Gestión de Valores</h2>
                <button @click="openCreateModal('value')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Nuevo Valor
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <template x-for="val in values" :key="val.id">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm relative group">
                        <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 rounded-xl flex items-center justify-center mb-4 text-blue-600">
                            <i :data-lucide="val.icon" class="w-5 h-5"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white mb-2" x-text="val.title"></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="val.description"></p>
                        <div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="editValue(val)" class="p-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-500 hover:text-blue-600">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <button @click="deleteValue(val.id)" class="p-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-500 hover:text-rose-600">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Tab Content: Testimonios -->
        <div x-show="activeTab === 'testimonials'" x-transition class="space-y-4">
            <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Reseñas de Clientes</h2>
                <button @click="openCreateModal('testimonial')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Nuevo Testimonio
                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-700 dark:text-gray-200">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Cliente</th>
                            <th class="px-6 py-4">Calificación</th>
                            <th class="px-6 py-4">Texto</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="t in testimonials" :key="t.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs text-gray-400" x-text="'#' + t.id"></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img :src="t.image_url || 'https://ui-avatars.com/api/?name='+t.name" class="w-10 h-10 rounded-full object-cover">
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-white" x-text="t.name"></p>
                                            <p class="text-xs text-gray-500" x-text="t.role || 'Cliente'"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex">
                                        <template x-for="starIndex in 5">
                                            <svg class="w-4 h-4" :class="starIndex <= t.rating ? 'text-yellow-400 fill-current' : 'text-gray-300 dark:text-gray-600'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                            </svg>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate" x-text="t.text"></td>
                                <td class="px-6 py-4 text-right">
                                     <div class="flex justify-end gap-2">
                                         <button @click="viewTestimonial(t)" class="p-2 text-gray-400 hover:text-blue-600" title="Ver detalle"><i data-lucide="eye" class="w-4 h-4"></i></button>
                                         <button @click="editTestimonial(t)" class="p-2 text-gray-400 hover:text-blue-600" title="Editar"><i data-lucide="edit-2" class="w-4 h-4"></i></button>
                                         <button @click="deleteTestimonial(t.id)" class="p-2 text-gray-400 hover:text-rose-600" title="Eliminar"><i data-lucide="trash" class="w-4 h-4"></i></button>
                                     </div>
                                 </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab Content: Galería -->
        <div x-show="activeTab === 'gallery'" x-transition class="space-y-4">
            <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Galería de Imágenes</h2>
                <button @click="openCreateModal('gallery')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition-all flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Subir Imagen
                </button>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <template x-for="item in gallery" :key="item.id">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-sm group relative">
                        <img :src="item.image_url" class="w-full h-40 object-cover">
                        <div class="p-3">
                            <p class="text-xs font-bold truncate dark:text-white" x-text="item.title || 'Sin título'"></p>
                            <p class="text-[10px] text-gray-400 mt-1" x-text="item.span_type"></p>
                        </div>
                        <div class="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="editGallery(item)" class="p-1.5 bg-black/50 text-white rounded-lg backdrop-blur-sm"><i data-lucide="edit" class="w-3.5 h-3.5"></i></button>
                            <button @click="deleteGallery(item.id)" class="p-1.5 bg-rose-500/80 text-white rounded-lg backdrop-blur-sm"><i data-lucide="trash-2" class="w-3.5 h-3.5"></i></button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- MODALS -->
        @include('landing.modals')

    </div>

    <!-- Initialize Global Data -->
    <script>
        window.landingData = {
            testimonials: @json($testimonials),
            gallery: @json($gallery),
            values: @json($values)
        };
    </script>
</x-app-layout>
