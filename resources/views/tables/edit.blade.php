<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Mesa {{ $table->table_number }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Actualizar información de la mesa</p>
            </div>
            <a href="{{ route('tables.index') }}"
                class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                Volver
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
            <form action="{{ route('tables.update', $table) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número de Mesa</label>
                    <input type="text" name="table_number" value="{{ old('table_number', $table->table_number) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Capacidad</label>
                    <input type="number" name="capacity" value="{{ old('capacity', $table->capacity) }}" min="1"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubicación</label>
                    <select name="location"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        <option value="Indoor" {{ old('location', $table->location) === 'Indoor' ? 'selected' : '' }}>
                            Adentro</option>
                        <option value="Outdoor" {{ old('location', $table->location) === 'Outdoor' ? 'selected' : '' }}>
                            Afuera</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                    <select name="status"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                        <option value="available" {{ old('status', $table->status) === 'available' ? 'selected' : '' }}>
                            Disponible</option>
                        <option value="reserved" {{ old('status', $table->status) === 'reserved' ? 'selected' : '' }}>
                            Ocupada</option>
                        <option value="maintenance"
                            {{ old('status', $table->status) === 'maintenance' ? 'selected' : '' }}>Mantenimiento
                        </option>
                    </select>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-colors shadow-sm">
                        Actualizar Mesa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
