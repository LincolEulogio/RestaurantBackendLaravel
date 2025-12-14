<x-app-layout>
    <div class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <a href="{{ route('reservations.index') }}"
                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detalles de Reservación
                        #{{ $reservation->id }}</h1>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 ml-14">Información completa de la reserva</p>
            </div>
            <div class="flex items-center gap-2">
                @if ($reservation->status === 'pending')
                    <form action="{{ route('reservations.update-status', $reservation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Confirmar Reserva
                        </button>
                    </form>
                @endif
                @if ($reservation->status !== 'cancelled')
                    <form action="{{ route('reservations.update-status', $reservation) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Status Badge -->
        <div class="flex items-center justify-between">
            <span class="text-sm text-gray-600 dark:text-gray-400">Estado</span>
            @php
                $statusClasses = match ($reservation->status) {
                    'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                    'confirmed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    default => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                };
            @endphp
            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClasses }}">
                {{ ucfirst($reservation->status) }}
            </span>
        </div>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            Creada el {{ $reservation->created_at->format('d/m/Y H:i') }}
        </span>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Customer Information -->
            <div
                class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Información del Cliente</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Nombre
                            Completo</label>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $reservation->customer_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Correo
                            Electrónico</label>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $reservation->customer_email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Teléfono</label>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $reservation->customer_phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Número de
                            Personas</label>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <p class="text-base font-semibold text-gray-900 dark:text-white">
                                {{ $reservation->party_size }} personas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reservation Details -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Detalles de Reserva</h2>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Fecha</label>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Hora</label>
                        <p class="text-base font-semibold text-blue-600 dark:text-blue-400">
                            {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Mesa
                            Asignada</label>
                        @if ($reservation->table)
                            <div
                                class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span class="font-semibold text-gray-900 dark:text-white">Mesa
                                    {{ $reservation->table->table_number }}</span>
                            </div>
                        @else
                            <p class="text-sm italic text-gray-400">Sin mesa asignada</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <!-- Special Request -->
        @if ($reservation->special_request)
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-50 dark:bg-amber-900/20 rounded-lg text-amber-600 dark:text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Solicitud Especial</h2>
                </div>
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $reservation->special_request }}</p>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('reservations.index') }}"
                class="px-6 py-2.5 bg-gray-600 text-white rounded-xl hover:bg-gray-700 font-semibold transition-colors">
                Volver a la Lista
            </a>
            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline"
                onsubmit="return confirm('¿Estás seguro de eliminar esta reserva?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-6 py-2.5 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    Eliminar Reserva
                </button>
            </form>
        </div>

    </div>
</x-app-layout>
