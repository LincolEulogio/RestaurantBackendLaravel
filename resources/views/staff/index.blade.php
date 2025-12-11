<x-app-layout>
    <div x-data="staffManager()" class="space-y-6">

        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Personal</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gestiona los usuarios y colaboradores del restaurante
                </p>
            </div>
            <button @click="openCreate()"
                class="bg-blue-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold  hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Usuario
            </button>
        </div>

        <!-- Feedback Messages handled globally by SweetAlert2 -->

        <!-- Stats -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Total Users -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Empleados</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</h3>
                    <p class="text-xs text-green-600 mt-1 flex items-center font-semibold">
                        <span class="bg-green-100 px-1.5 py-0.5 rounded mr-1">Activos</span>
                    </p>
                </div>
                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Role Distribution -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Distribución</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $waiterCount }} <span
                            class="text-sm font-normal text-gray-400">Meseros</span></h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $chefCount }} Cocina · {{ $adminCount }} Admin</p>
                </div>
                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <!-- Active Roles -->
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Roles definidos</p>
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white">5</h3>
                    <p class="text-xs text-gray-400 mt-1">Admin, Gerente, Chef, Mesero, Cajero</p>
                </div>
                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="space-y-6">

            <!-- Users Directory Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th
                                class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Empleado</th>
                            <th
                                class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Rol</th>
                            <th
                                class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Email</th>
                            <th
                                class="py-4 px-6 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff"
                                            class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-600"
                                            alt="">
                                        <div>
                                            <p
                                                class="font-bold text-gray-900 dark:text-white text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                {{ $user->name }}
                                            </p>
                                            <p class="text-xs text-gray-400">ID: {{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $user->role === 'admin' ? 'bg-gray-900 text-white' : '' }}
                                    {{ $user->role === 'manager' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $user->role === 'chef' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $user->role === 'waiter' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $user->role === 'cashier' ? 'bg-green-100 text-green-700' : '' }}
                                    ">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- Edit Button -->
                                        <button @click="openEdit({{ $user }})"
                                            class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('staff.destroy', $user->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>

        </div>

        <!-- Modal Overlay -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">

            <!-- Backdrop -->
            <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity"
                @click="openModal = false">
            </div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                <!-- Modal Panel -->
                <div x-show="openModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">


                    <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-white"
                                    id="modal-title">
                                    <span x-text="isEdit ? 'Editar Usuario' : 'Nuevo Usuario'"></span>
                                </h3>
                                <div class="mt-6">

                                    <!-- FORM -->
                                    <form :action="getActionUrl('{{ url('staff') }}')" method="POST"
                                        id="userForm">
                                        @csrf
                                        <input type="hidden" name="_method" value="PUT" :disabled="!isEdit">

                                        <!-- Name -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nombre</label>
                                            <input type="text" name="name" x-model="form.name" required
                                                class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-xl w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email</label>
                                            <input type="email" name="email" x-model="form.email" required
                                                class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-xl w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>

                                        <!-- Role -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Rol</label>
                                            <select name="role" x-model="form.role" required
                                                class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-xl w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                <option value="" disabled>Seleccionar Rol</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->slug }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-4">
                                            <label
                                                class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                                                <span
                                                    x-text="isEdit ? 'Contraseña (Dejar en blanco para mantener)' : 'Contraseña'"></span>
                                            </label>
                                            <input type="password" name="password" x-model="form.password"
                                                :required="!isEdit"
                                                class="shadow-sm border border-gray-300 dark:border-gray-600 rounded-xl w-full py-2 px-3 text-gray-700 dark:text-white dark:bg-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                        <button type="submit" form="userForm"
                            class="inline-flex w-full justify-center rounded-xl bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Guardar</button>
                        <button @click="openModal = false" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
