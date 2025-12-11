<x-app-layout>
    <div x-data="settingsManager()" class="space-y-6">
        <!-- Header & Tabs -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Configuración General</h1>
            <div class="mt-4 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="#"
                        class="border-blue-500 text-blue-600 dark:text-blue-400 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">General</a>
                    <a href="#"
                        class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">Usuarios</a>
                    <a href="#"
                        class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">Facturación</a>
                    <a href="#"
                        class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">Integraciones</a>
                </nav>
            </div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Logo Empresarial -->
            <x-ui.card class="p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Logo Empresarial</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sube el logo de tu restaurante para
                    personalizar el sistema</p>

                <div class="mt-4 flex items-center gap-6">
                    <div
                        class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-lg flex items-center justify-center border border-dashed border-gray-300 dark:border-gray-600 overflow-hidden relative">
                        @if (isset($settings['logo']) && $settings['logo'])
                            <img src="{{ asset('storage/' . $settings['logo']) }}" class="w-full h-full object-contain">
                        @else
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <div class="relative">
                            <input type="file" name="logo" id="logo-upload" class="hidden" accept="image/*"
                                onchange="document.getElementById('logo-preview-text').innerText = this.files[0].name">
                            <x-ui.button type="button" onclick="document.getElementById('logo-upload').click()"
                                class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                Subir Logo
                            </x-ui.button>
                        </div>
                        <p id="logo-preview-text" class="text-xs text-gray-400 mt-2">PNG, JPG hasta 2MB</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Datos del Restaurante -->
            <x-ui.card class="p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Datos del Restaurante</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Información general de tu negocio</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del
                            Restaurante</label>
                        <x-ui.input name="restaurant_name" value="{{ $settings['restaurant_name'] ?? '' }}" />
                    </div>
                    <!-- Cuisine Type -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Cocina</label>
                        <select name="restaurant_cuisine_type"
                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccionar tipo</option>
                            <option value="Italiana"
                                {{ ($settings['restaurant_cuisine_type'] ?? '') == 'Italiana' ? 'selected' : '' }}>
                                Italiana</option>
                            <option value="Mexicana"
                                {{ ($settings['restaurant_cuisine_type'] ?? '') == 'Mexicana' ? 'selected' : '' }}>
                                Mexicana</option>
                            <option value="Japonesa"
                                {{ ($settings['restaurant_cuisine_type'] ?? '') == 'Japonesa' ? 'selected' : '' }}>
                                Japonesa</option>
                        </select>
                    </div>
                    <!-- Phone -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                        <x-ui.input name="restaurant_phone" value="{{ $settings['restaurant_phone'] ?? '' }}" />
                    </div>
                    <!-- Contact Email -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email de Contacto</label>
                        <x-ui.input name="restaurant_email" value="{{ $settings['restaurant_email'] ?? '' }}" />
                    </div>
                    <!-- Address -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Dirección Completa</label>
                        <x-ui.input name="restaurant_address" value="{{ $settings['restaurant_address'] ?? '' }}" />
                    </div>
                    <!-- Timezone -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Zona Horaria</label>
                        <select name="restaurant_timezone"
                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="America/Lima"
                                {{ ($settings['restaurant_timezone'] ?? '') == 'America/Lima' ? 'selected' : '' }}>
                                America/Lima (Perú)</option>
                        </select>
                    </div>
                    <!-- Currency -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Moneda</label>
                        <select name="restaurant_currency"
                            class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="PEN - Sol Peruano"
                                {{ ($settings['restaurant_currency'] ?? '') == 'PEN - Sol Peruano' ? 'selected' : '' }}>
                                PEN - Sol Peruano</option>
                        </select>
                    </div>
                </div>
            </x-ui.card>

            <!-- Impresoras -->
            <x-ui.card class="p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Impresoras</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configura las impresoras para tickets y
                            comandas</p>
                    </div>
                    <x-ui.button type="button"
                        class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 h-9">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Agregar Impresora
                    </x-ui.button>
                </div>

                <div class="space-y-4">
                    @forelse($printers as $printer)
                        <div
                            class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50/50 dark:bg-gray-700/50">
                            <div class="flex items-center gap-4">
                                <div
                                    class="p-2 bg-blue-100/50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $printer->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        {{ ucfirst($printer->type) }} -
                                        {{ $printer->connection_type }}</p>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $printer->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                                        <span
                                            class="w-1.5 h-1.5 {{ $printer->is_active ? 'bg-green-600' : 'bg-gray-500' }} rounded-full mr-1.5"></span>
                                        {{ $printer->is_active ? 'Conectada' : 'Desconectado' }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="text-gray-400 hover:text-gray-600 p-1"><svg
                                        class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg></button>
                                <button type="button" class="text-gray-400 hover:text-red-500 p-1"><svg
                                        class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg></button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-4 text-gray-500 text-sm">
                            No hay impresoras configuradas.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>

            <!-- Métodos de Pago -->
            <x-ui.card class="p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Métodos de Pago</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Activa los métodos de pago disponibles en tu
                    restaurante</p>

                <div class="mt-6 space-y-4">
                    <!-- Efectivo -->
                    <div
                        class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Efectivo</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pago en efectivo</p>
                            </div>
                        </div>
                        <x-toggle name="payment_cash"
                            checked="{{ $settings['payment_cash'] ?? 0 ? 'true' : 'false' }}" />
                    </div>
                    <!-- Tarjeta -->
                    <div
                        class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Tarjeta</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Débito y crédito</p>
                            </div>
                        </div>
                        <x-toggle name="payment_card"
                            checked="{{ $settings['payment_card'] ?? 0 ? 'true' : 'false' }}" />
                    </div>
                    <!-- Transferencia -->
                    <div
                        class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Transferencia</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transferencia bancaria</p>
                            </div>
                        </div>
                        <x-toggle name="payment_transfer"
                            checked="{{ $settings['payment_transfer'] ?? 0 ? 'true' : 'false' }}" />
                    </div>
                    <!-- Digital -->
                    <div
                        class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg text-orange-600 dark:text-orange-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-white">Pago Digital</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">PayPal, Mercado Pago, etc.</p>
                            </div>
                        </div>
                        <x-toggle name="payment_digital"
                            checked="{{ $settings['payment_digital'] ?? 0 ? 'true' : 'false' }}" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Preferencias del Sistema -->
            <x-ui.card class="p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Preferencias del Sistema</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configura el comportamiento general del
                    sistema</p>

                <div class="mt-6 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Impresión Automática</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Imprimir tickets automáticamente al
                                confirmar orden</p>
                        </div>
                        <x-toggle name="system_auto_print"
                            checked="{{ $settings['system_auto_print'] ?? 0 ? 'true' : 'false' }}" />
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Notificaciones Sonoras</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Reproducir sonido al recibir nuevas
                                órdenes</p>
                        </div>
                        <x-toggle name="system_sound_notifications"
                            checked="{{ $settings['system_sound_notifications'] ?? 0 ? 'true' : 'false' }}" />
                    </div>
                </div>
            </x-ui.card>

            <!-- Global Save Button -->
            <div class="flex justify-end pb-8">
                <x-ui.button
                    class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 w-full sm:w-auto h-12 text-lg">
                    Guardar Toda la Configuración
                </x-ui.button>
            </div>
        </form>
    </div>
</x-app-layout>
