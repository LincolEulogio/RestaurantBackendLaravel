<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'RestaurantOS') }} - Sistema de Administración</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-gradient-to-br from-amber-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 min-h-screen">

    <!-- Header with Auth Links -->
    <header class="absolute top-0 left-0 right-0 z-10">
        <div class="max-w-7xl mx-auto px-6 py-6">
            <nav class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">RestaurantOS</span>
                </div>

                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-orange-600/30">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-6 py-2.5 text-gray-700 dark:text-gray-300 hover:text-orange-600 dark:hover:text-orange-400 font-medium transition-colors">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-orange-600/30">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex items-center justify-center min-h-screen px-6 py-20">
        <div class="max-w-6xl mx-auto w-full">

            <!-- Hero Section -->
            <div class="text-center mb-16">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                        </path>
                    </svg>
                    Sistema Profesional de Gestión
                </div>

                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    Administra tu
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600">
                        Restaurante
                    </span>
                </h1>

                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10">
                    Sistema completo de gestión para restaurantes. Controla pedidos, inventario, menú, personal y
                    reportes desde una sola plataforma moderna y eficiente.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @guest
                        <a href="{{ route('login') }}"
                            class="px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-semibold text-lg transition-all shadow-xl shadow-orange-600/30 hover:shadow-2xl hover:shadow-orange-600/40 hover:scale-105">
                            Comenzar Ahora
                            <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        <a href="#features"
                            class="px-8 py-4 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white rounded-xl font-semibold text-lg transition-all shadow-lg border border-gray-200 dark:border-gray-700">
                            Ver Características
                        </a>
                    @else
                        <a href="{{ url('/dashboard') }}"
                            class="px-8 py-4 bg-orange-600 hover:bg-orange-700 text-white rounded-xl font-semibold text-lg transition-all shadow-xl shadow-orange-600/30 hover:shadow-2xl hover:shadow-orange-600/40 hover:scale-105">
                            Ir al Dashboard
                            <svg class="inline-block w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Features Grid -->
            <div id="features" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-20">

                <!-- Feature 1: Dashboard -->
                <div
                    class="group p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-800">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-orange-500 to-amber-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Dashboard Intuitivo</h3>
                    <p class="text-gray-600 dark:text-gray-300">Visualiza métricas en tiempo real, ventas del día y
                        estadísticas clave de tu restaurante.</p>
                </div>

                <!-- Feature 2: Orders -->
                <div
                    class="group p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-800">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Gestión de Pedidos</h3>
                    <p class="text-gray-600 dark:text-gray-300">Administra pedidos de manera eficiente con seguimiento
                        en tiempo real desde la cocina hasta la mesa.</p>
                </div>

                <!-- Feature 3: Menu -->
                <div
                    class="group p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-800">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Menú Digital</h3>
                    <p class="text-gray-600 dark:text-gray-300">Actualiza tu menú fácilmente, gestiona categorías y
                        precios con solo unos clics.</p>
                </div>

                <!-- Feature 4: Inventory -->
                <div
                    class="group p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-800">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Control de Inventario</h3>
                    <p class="text-gray-600 dark:text-gray-300">Mantén el control de tu stock, recibe alertas de
                        productos bajos y optimiza tus compras.</p>
                </div>

                <!-- Feature 5: Reports -->
                <div
                    class="group p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-800">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-red-500 to-orange-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Reportes y Análisis</h3>
                    <p class="text-gray-600 dark:text-gray-300">Genera reportes detallados de ventas, productos más
                        vendidos y análisis financiero.</p>
                </div>

                <!-- Feature 6: Settings -->
                <div
                    class="group p-8 bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all border border-gray-100 dark:border-gray-700 hover:border-orange-200 dark:hover:border-orange-800">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Gestión de Personal</h3>
                    <p class="text-gray-600 dark:text-gray-300">Administra roles, permisos y personal con un sistema de
                        control de acceso basado en roles.</p>
                </div>

            </div>

            <!-- CTA Section -->
            <div class="mt-20 text-center">
                <div class="p-12 bg-gradient-to-r from-orange-600 to-amber-600 rounded-3xl shadow-2xl">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        ¿Listo para optimizar tu restaurante?
                    </h2>
                    <p class="text-xl text-orange-100 mb-8 max-w-2xl mx-auto">
                        Únete a cientos de restaurantes que ya confían en RestaurantOS para gestionar sus operaciones
                        diarias.
                    </p>
                    @guest
                        <a href="{{ route('register') }}"
                            class="inline-block px-10 py-4 bg-white hover:bg-gray-50 text-orange-600 rounded-xl font-bold text-lg transition-all shadow-xl hover:shadow-2xl hover:scale-105">
                            Crear Cuenta Gratis
                        </a>
                    @else
                        <a href="{{ url('/dashboard') }}"
                            class="inline-block px-10 py-4 bg-white hover:bg-gray-50 text-orange-600 rounded-xl font-bold text-lg transition-all shadow-xl hover:shadow-2xl hover:scale-105">
                            Ir al Dashboard
                        </a>
                    @endguest
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="py-8 text-center text-gray-600 dark:text-gray-400">
        <p>&copy; {{ date('Y') }} RestaurantOS. Sistema de Administración de Restaurantes.</p>
    </footer>

</body>

</html>
