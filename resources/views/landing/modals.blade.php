<!-- Modal Container -->
<div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div @click="closeModal()" class="fixed inset-0 bg-black/30 backdrop-blur-sm transition-opacity"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-700">
            
            <!-- FORM: VALORES -->
            <form x-show="modalType === 'value'" @submit.prevent="saveValue">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4" x-text="isEdit ? 'Editar Valor' : 'Nuevo Valor'"></h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Título</label>
                            <input type="text" x-model="valueForm.title" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción</label>
                            <textarea rows="3" x-model="valueForm.description" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Icono (Lucide)</label>
                                <input type="text" x-model="valueForm.icon" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Orden</label>
                                <input type="number" x-model="valueForm.order" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                    <button @click="closeModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-700 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                </div>
            </form>

            <!-- FORM: TESTIMONIOS -->
            <form x-show="modalType === 'testimonial'" @submit.prevent="saveTestimonial">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4" x-text="isEdit ? 'Editar Testimonio' : 'Nuevo Testimonio'"></h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre</label>
                                <input type="text" x-model="testimonialForm.name" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Cargo/Rol</label>
                                <input type="text" x-model="testimonialForm.role" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Calificación (1-5)</label>
                                <input type="number" x-model="testimonialForm.rating" min="1" max="5" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Avatar</label>
                                <input type="file" @change="testimonialForm.image = $event.target.files[0]" class="block w-full text-xs text-gray-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Comentario</label>
                            <textarea rows="4" x-model="testimonialForm.text" required class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                    <button @click="closeModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-700 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                </div>
            </form>

            <!-- FORM: GALERÍA -->
            <form x-show="modalType === 'gallery'" @submit.prevent="saveGallery">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4" x-text="isEdit ? 'Editar Imagen' : 'Nueva Imagen'"></h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Imagen</label>
                            <input type="file" @change="galleryForm.image = $event.target.files[0]" class="block w-full text-xs text-gray-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Subtítulo / Alt Text</label>
                            <input type="text" x-model="galleryForm.title" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Tipo de Celda</label>
                                <select x-model="galleryForm.span_type" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                                    <option value="col-span-1 row-span-1">Normal (1x1)</option>
                                    <option value="col-span-2 row-span-1">Ancho (2x1)</option>
                                    <option value="col-span-1 row-span-2">Alto (1x2)</option>
                                    <option value="col-span-2 row-span-2">Grande (2x2)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Orden</label>
                                <input type="number" x-model="galleryForm.order" class="w-full rounded-xl border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- same buttons as above -->
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3 border-t border-gray-100 dark:border-gray-700">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                    <button @click="closeModal()" type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-700 dark:border-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">Cancelar</button>
                </div>
            </form>

        </div>
    </div>
</div>
