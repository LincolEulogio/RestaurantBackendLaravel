<x-app-layout>
    <div x-data="{
        activeTab: localStorage.getItem('settingsActiveTab') || 'general',
        updateTab(tab) {
            this.activeTab = tab;
            localStorage.setItem('settingsActiveTab', tab);
        }
    }" x-init="$watch('activeTab', val => localStorage.setItem('settingsActiveTab', val))" class="space-y-6">
        <!-- Header & Tabs -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Configuración General</h1>
            <div class="mt-4 border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a @click.prevent="updateTab('general')" href="#"
                        :class="activeTab === 'general' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">General</a>
                    <a @click.prevent="updateTab('payment')" href="#"
                        :class="activeTab === 'payment' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">Métodos de Pago</a>
                    <a @click.prevent="updateTab('printers')" href="#"
                        :class="activeTab === 'printers' ? 'border-blue-500 text-blue-600 dark:text-blue-400' :
                            'border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300'"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">Impresoras</a>
                </nav>
            </div>
        </div>

        <!-- Tab: General -->
        <div x-show="activeTab === 'general'" x-cloak>
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
                                <img src="{{ asset('storage/' . $settings['logo']) }}"
                                    class="w-full h-full object-contain">
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Email de
                                Contacto</label>
                            <x-ui.input name="restaurant_email" value="{{ $settings['restaurant_email'] ?? '' }}" />
                        </div>
                        <!-- Address -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Dirección
                                Completa</label>
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
                        Guardar Configuración General
                    </x-ui.button>
                </div>
            </form>
        </div>

        <!-- Tab: Payment Methods -->
        <div x-show="activeTab === 'payment'" x-cloak>
            <x-ui.card class="p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Métodos de Pago</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gestiona los métodos de pago
                            disponibles
                            en tu restaurante</p>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($paymentMethods as $method)
                        <div
                            class="flex items-center justify-between p-4 border border-gray-100 dark:border-gray-700 rounded-lg bg-gray-50/50 dark:bg-gray-700/50">
                            <div class="flex items-center gap-4">
                                <div
                                    class="p-2 rounded-lg 
                                    @if ($method->type === 'cash') bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400
                                    @elseif($method->type === 'card') bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400
                                    @elseif($method->type === 'transfer') bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400
                                    @else bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 @endif">
                                    @if ($method->type === 'cash')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                    @elseif($method->type === 'card')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                    @elseif($method->type === 'transfer')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z">
                                            </path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $method->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $method->description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4" x-data="{
                                active: {{ $method->is_active ? 'true' : 'false' }},
                                loading: false,
                                async toggle() {
                                    if (this.loading) return;
                                    this.loading = true;
                            
                                    try {
                                        const response = await fetch('{{ route('payment-methods.toggle', $method) }}', {
                                            method: 'PATCH',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            }
                                        });
                            
                                        if (response.ok) {
                                            const data = await response.json();
                                            this.active = data.is_active;
                                        } else {
                                            // Revert on error or show notification
                                            console.error('Failed to toggle');
                                        }
                                    } catch (e) {
                                        console.error(e);
                                    } finally {
                                        this.loading = false;
                                    }
                                }
                            }">
                                <button type="button" @click="toggle()" :disabled="loading"
                                    class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                    :class="active ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-700'">
                                    <span
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                        :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center p-4 text-gray-500 text-sm">
                            No hay métodos de pago configurados.
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Tab: Printers -->
        <div x-show="activeTab === 'printers'" x-cloak>
            <x-ui.card class="p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Impresoras</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Administra las impresoras para tickets
                            y cocina</p>
                    </div>
                    <x-ui.button @click="$dispatch('open-modal', 'add-printer-modal')"
                        class="bg-blue-600 hover:bg-blue-700 text-white">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Nueva Impresora
                    </x-ui.button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($printers as $printer)
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 bg-white dark:bg-gray-800 shadow-sm relative group">
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                        </path>
                                    </svg>
                                </div>
                                <div x-data="{
                                    active: {{ $printer->is_active ? 'true' : 'false' }},
                                    loading: false,
                                    async toggle() {
                                        if (this.loading) return;
                                        this.loading = true;
                                        try {
                                            const response = await fetch('{{ route('printers.toggle', $printer) }}', {
                                                method: 'PATCH',
                                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                                            });
                                            if (response.ok) {
                                                const data = await response.json();
                                                this.active = data.is_active;
                                            }
                                        } catch (e) {
                                            console.error(e);
                                        } finally {
                                            this.loading = false;
                                        }
                                    }
                                }">
                                    <button type="button" @click="toggle()" :disabled="loading"
                                        class="relative inline-flex items-center h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        :class="active ? 'bg-green-500' : 'bg-gray-200 dark:bg-gray-700'">
                                        <span
                                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                            :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                                    </button>
                                </div>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $printer->name }}
                            </h3>
                            <div class="space-y-1 text-sm text-gray-500 dark:text-gray-400">
                                <p class="flex items-center">
                                    <span class="w-24 font-medium">Tipo:</span>
                                    <span
                                        class="capitalize">{{ $printer->type === 'ticket' ? 'Caja / Ticket' : ($printer->type === 'kitchen' ? 'Cocina' : 'Bar') }}</span>
                                </p>
                                <p class="flex items-center">
                                    <span class="w-24 font-medium">Conexión:</span>
                                    <span class="capitalize">{{ $printer->connection_type }}</span>
                                </p>
                                @if ($printer->connection_type === 'network')
                                    <p class="flex items-center">
                                        <span class="w-24 font-medium">IP:</span>
                                        <span
                                            class="font-mono">{{ $printer->ip_address }}:{{ $printer->port }}</span>
                                    </p>
                                @endif
                            </div>

                            <div
                                class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                                <button @click="$dispatch('open-modal', 'edit-printer-{{ $printer->id }}')"
                                    class="p-2 text-gray-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <form action="{{ route('printers.destroy', $printer) }}" method="POST"
                                    onsubmit="return confirm('¿Estás seguro de eliminar esta impresora?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <x-modal name="edit-printer-{{ $printer->id }}" focusable>
                                <form method="POST" action="{{ route('printers.update', $printer) }}"
                                    class="p-6">
                                    @csrf
                                    @method('PUT')
                                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                        Editar Impresora
                                    </h2>
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <x-input-label for="name_{{ $printer->id }}" value="Nombre" />
                                            <x-text-input id="name_{{ $printer->id }}" name="name"
                                                type="text" class="mt-1 block w-full" :value="$printer->name" required />
                                        </div>
                                        <div>
                                            <x-input-label for="type_{{ $printer->id }}" value="Tipo de Uso" />
                                            <select name="type" id="type_{{ $printer->id }}"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                                <option value="ticket"
                                                    {{ $printer->type == 'ticket' ? 'selected' : '' }}>Caja / Ticket
                                                </option>
                                                <option value="kitchen"
                                                    {{ $printer->type == 'kitchen' ? 'selected' : '' }}>Cocina</option>
                                                <option value="bar"
                                                    {{ $printer->type == 'bar' ? 'selected' : '' }}>Bar</option>
                                            </select>
                                        </div>
                                        <div>
                                            <x-input-label for="connection_type_{{ $printer->id }}"
                                                value="Conexión" />
                                            <select name="connection_type" id="connection_type_{{ $printer->id }}"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                                x-data="{ type: '{{ $printer->connection_type }}' }" x-model="type"
                                                @change="$dispatch('conn-change-{{ $printer->id }}', type)">
                                                <option value="network">Red / Ethernet / WiFi</option>
                                                <option value="usb">USB</option>
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-3 gap-4" x-data="{ show: '{{ $printer->connection_type }}' === 'network' }"
                                            @conn-change-{{ $printer->id }}.window="show = ($event.detail === 'network')">
                                            <div class="col-span-2" x-show="show">
                                                <x-input-label for="ip_{{ $printer->id }}" value="Dirección IP" />
                                                <x-text-input id="ip_{{ $printer->id }}" name="ip_address"
                                                    type="text" class="mt-1 block w-full" :value="$printer->ip_address"
                                                    placeholder="192.168.1.200" />
                                            </div>
                                            <div x-show="show">
                                                <x-input-label for="port_{{ $printer->id }}" value="Puerto" />
                                                <x-text-input id="port_{{ $printer->id }}" name="port"
                                                    type="number" class="mt-1 block w-full" :value="$printer->port"
                                                    placeholder="9100" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')"
                                            type="button">Cancelar</x-secondary-button>
                                        <x-primary-button class="ml-3">Guardar Cambios</x-primary-button>
                                    </div>
                                </form>
                            </x-modal>
                        </div>
                    @empty
                        <div
                            class="col-span-3 text-center py-12 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay impresoras</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza agregando una nueva
                                impresora a tu sistema.</p>
                            <div class="mt-6">
                                <x-ui.button @click="$dispatch('open-modal', 'add-printer-modal')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Impresora
                                </x-ui.button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Add Printer Modal -->
        <x-modal name="add-printer-modal" focusable>
            <form method="POST" action="{{ route('printers.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    Agregar Nueva Impresora
                </h2>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <x-input-label for="new_name" value="Nombre" />
                        <x-text-input id="new_name" name="name" type="text" class="mt-1 block w-full"
                            placeholder="Ej: Impresora Caja Principal" required />
                    </div>
                    <div>
                        <x-input-label for="new_type" value="Tipo de Uso" />
                        <select name="type" id="new_type"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="ticket">Caja / Ticket</option>
                            <option value="kitchen">Cocina</option>
                            <option value="bar">Bar</option>
                        </select>
                    </div>
                    <div x-data="{ type: 'network' }">
                        <div>
                            <x-input-label for="new_connection_type" value="Conexión" />
                            <select name="connection_type" id="new_connection_type"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                x-model="type">
                                <option value="network">Red / Ethernet / WiFi</option>
                                <option value="usb">USB</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mt-4" x-show="type === 'network'">
                            <div class="col-span-2">
                                <x-input-label for="new_ip" value="Dirección IP" />
                                <x-text-input id="new_ip" name="ip_address" type="text"
                                    class="mt-1 block w-full" placeholder="192.168.1.200" />
                            </div>
                            <div>
                                <x-input-label for="new_port" value="Puerto" />
                                <x-text-input id="new_port" name="port" type="number" class="mt-1 block w-full"
                                    value="9100" />
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Hidden active field -->
                <input type="hidden" name="is_active" value="1">

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')" type="button">Cancelar</x-secondary-button>
                    <x-primary-button class="ml-3">Guardar Impresora</x-primary-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
