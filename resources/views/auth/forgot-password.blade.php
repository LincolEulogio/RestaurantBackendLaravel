<x-guest-layout>
    <div class="flex flex-col items-center sm:pt-0">
        <div class="mb-6 text-center">
            <div class="flex justify-center mb-4">
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.5 15.5a9.928 9.928 0 01-2.929 2.929m-1.414 1.414a2 2 0 01-2.071 0l-.707-.707a2 2 0 010-2.071l.707-.707m12.728-9.9a1.998 1.998 0 00-2.828 0l-.707.707a2 2 0 002.828 2.828l.707-.707a1.998 1.998 0 000-2.828z" />
                    </svg>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Recuperar Contraseña</h2>
        </div>

        <div class="w-full sm:max-w-md bg-white overflow-hidden">
            <div class="mb-4 text-sm text-gray-600 text-center">
                {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus
                            class="block w-full pl-10 pr-3 py-2.5 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-0 focus:border-blue-500 sm:text-sm placeholder-gray-400"
                            placeholder="tu@restaurante.com">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="font-medium text-sm text-blue-600 hover:text-blue-500">
                        Volver al inicio de sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
