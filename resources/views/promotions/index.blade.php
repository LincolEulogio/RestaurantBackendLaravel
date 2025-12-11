<x-app-layout>
    <div class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Promociones</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona las ofertas y promociones especiales</p>
            </div>
            <a href="{{ route('promotions.create') }}"
                class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Nueva Promoción
            </a>
        </div>

        <!-- Promotions List -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-700 dark:text-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">Imagen</th>
                            <th scope="col" class="px-6 py-4 font-bold">Título</th>
                            <th scope="col" class="px-6 py-4 font-bold">Descuento</th>
                            <th scope="col" class="px-6 py-4 font-bold">Vigencia</th>
                            <th scope="col" class="px-6 py-4 font-bold">Estado</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($promotions as $promotion)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    @if ($promotion->image)
                                        <img src="{{ asset('storage/' . $promotion->image) }}"
                                            alt="{{ $promotion->title }}" class="h-12 w-20 object-cover rounded-lg">
                                    @else
                                        <div
                                            class="h-12 w-20 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center text-xs text-gray-500 dark:text-gray-300">
                                            Sin img
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-gray-200">{{ $promotion->title }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ Str::limit($promotion->description, 50) }}</div>
                                    @if ($promotion->badge_label)
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                            {{ $promotion->badge_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $promotion->discount_percent ? $promotion->discount_percent . '%' : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-500 dark:text-gray-300">
                                        <div class="flex items-center gap-1">
                                            <span class="font-semibold">Inicio:</span>
                                            {{ $promotion->start_date ? $promotion->start_date->format('d/m/Y') : 'N/A' }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <span class="font-semibold">Fin:</span>
                                            {{ $promotion->end_date ? $promotion->end_date->format('d/m/Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold {{ $promotion->status ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ $promotion->status ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('promotions.edit', $promotion) }}"
                                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:text-blue-200 dark:hover:bg-blue-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('promotions.destroy', $promotion) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar esta promoción?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:text-rose-200 dark:hover:bg-rose-50 rounded-lg transition-colors">
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
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-200">
                                    No hay promociones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
