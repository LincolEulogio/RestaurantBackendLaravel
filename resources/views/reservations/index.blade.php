<x-app-layout>
    <div x-data="reservationManager()" class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestión de Reservaciones</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Administra las reservas de mesas</p>
            </div>
            <a href="{{ route('tables.index') }}"
                class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                Gestionar Mesas
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Reservations -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Reservas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalReservations }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Pendientes</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $pendingReservations }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Para Hoy</p>
                        <p class="text-3xl font-bold text-orange-600">{{ $todayReservations }}</p>
                    </div>
                    <div
                        class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Confirmed -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Confirmadas</p>
                        <p class="text-3xl font-bold text-green-600">{{ $confirmedReservations }}</p>
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
            {{-- Canceladas--}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Canceladas</p>
                        <p class="text-3xl font-bold text-red-600">{{ $cancelledReservations }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm">
            <form method="GET" action="{{ route('reservations.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por cliente, email o teléfono..."
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder:text-gray-500 dark:placeholder:text-gray-400 border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                </div>
                <div>
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                </div>
                <div>
                    <select name="table_id"
                        class="px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                        <option value="all">Todas las mesas</option>
                        @foreach ($tables as $table)
                            <option value="{{ $table->id }}"
                                {{ request('table_id') == $table->id ? 'selected' : '' }}>
                                Mesa {{ $table->table_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status"
                        class="px-4 py-2.5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 rounded-xl focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400 transition-colors">
                        <option value="all">Todos los estados</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado
                        </option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('reservations.index') }}"
                    class="px-6 py-2.5 bg-gray-600 text-white rounded-xl hover:bg-gray-700 font-semibold transition-colors inline-flex items-center justify-center">
                    Resetear
                </a>
            </form>
        </div>

        <!-- Reservations Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase text-gray-700 dark:text-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-bold">#</th>
                            <th scope="col" class="px-6 py-4 font-bold">Cliente</th>
                            <th scope="col" class="px-6 py-4 font-bold">Fecha y Hora</th>
                            <th scope="col" class="px-6 py-4 font-bold">Mesa</th>
                            <th scope="col" class="px-6 py-4 font-bold">Personas</th>
                            <th scope="col" class="px-6 py-4 font-bold">Estado</th>
                            <th scope="col" class="px-6 py-4 font-bold">Solicitud Especial</th>
                            <th scope="col" class="px-6 py-4 font-bold text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($reservations as $reservation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="font-mono text-sm font-bold text-gray-900 dark:text-white">{{ $reservation->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $reservation->customer_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $reservation->customer_email }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $reservation->customer_phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $reservation->reservation_date }}</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400">
                                        {{ $reservation->reservation_time }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($reservation->table)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Mesa {{ $reservation->table->table_number }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400">Sin mesa</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-1 text-gray-900 dark:text-white">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        {{ $reservation->party_size }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold
                                        @if ($reservation->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400
                                        @elseif($reservation->status == 'confirmed') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($reservation->status == 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                        @else bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($reservation->special_request)
                                        <span class="text-xs text-gray-600 dark:text-gray-300 truncate max-w-xs block"
                                            title="{{ $reservation->special_request }}">
                                            {{ Str::limit($reservation->special_request, 30) }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- View Details -->
                                        <a href="{{ route('reservations.show', $reservation) }}"
                                            class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 dark:text-blue-400 dark:hover:text-blue-300 rounded-lg transition-colors"
                                            title="Ver Detalles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        @if ($reservation->status === 'pending')
                                            <form action="{{ route('reservations.update-status', $reservation) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit"
                                                    class="p-2 text-green-600 hover:text-green-700 hover:bg-green-50 dark:hover:bg-green-900/20 dark:text-green-400 dark:hover:text-green-300 rounded-lg transition-colors"
                                                    title="Confirmar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($reservation->status !== 'cancelled')
                                            <form action="{{ route('reservations.update-status', $reservation) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit"
                                                    class="p-2 text-orange-600 hover:text-orange-700 hover:bg-orange-50 dark:hover:bg-orange-900/20 dark:text-orange-400 dark:hover:text-orange-300 rounded-lg transition-colors"
                                                    title="Cancelar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('reservations.destroy', $reservation) }}"
                                            method="POST" class="inline"
                                            onsubmit="return confirm('¿Estás seguro de eliminar esta reserva?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:hover:text-red-400 rounded-lg transition-colors"
                                                title="Eliminar">
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
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No se encontraron reservaciones
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Professional Pagination -->
        @if ($reservations->hasPages())
            <div>
                <div class="flex justify-center py-4">
                    <nav class="flex items-center gap-2" aria-label="Pagination">
                        <!-- Previous Button -->
                        @if ($reservations->onFirstPage())
                            <button disabled
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 opacity-50 cursor-not-allowed transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        @else
                            <a href="{{ $reservations->previousPageUrl() }}"
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 hover:text-white hover:border-gray-600 transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        <div class="flex items-center gap-2">
                            @foreach (range(1, $reservations->lastPage()) as $page)
                                @if ($page == $reservations->currentPage())
                                    <button
                                        class="w-10 h-10 rounded-lg text-sm font-bold bg-blue-600 text-white shadow-lg shadow-blue-500/30 border-0 flex items-center justify-center">
                                        {{ $page }}
                                    </button>
                                @else
                                    <a href="{{ $reservations->url($page) }}"
                                        class="w-10 h-10 rounded-lg text-sm font-bold bg-gray-800 text-gray-400 border border-gray-700 hover:text-white hover:border-gray-600 transition-all flex items-center justify-center">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        <!-- Next Button -->
                        @if ($reservations->hasMorePages())
                            <a href="{{ $reservations->nextPageUrl() }}"
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 hover:text-white hover:border-gray-600 transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <button disabled
                                class="p-2 rounded-lg border border-gray-700 bg-gray-800 text-gray-400 opacity-50 cursor-not-allowed transition-all w-10 h-10 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @endif
                    </nav>
                </div>
            </div>
        @endif

    </div>

    <script>
        function reservationManager() {
            return {
                init() {
                    console.log('Reservation Manager initialized');
                }
            }
        }
    </script>
</x-app-layout>
