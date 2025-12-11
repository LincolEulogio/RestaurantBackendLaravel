<x-app-layout>
    <div class="space-y-6">
        <!-- Header with Date -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Dashboard
                    @if ($userRole === 'waiter')
                        - Mesero
                    @elseif($userRole === 'kitchen')
                        - Cocina
                    @elseif($userRole === 'cashier')
                        - Caja
                    @endif
                </h1>
                <p class="text-sm text-gray-500 mt-1">Bienvenido al panel de control -
                    {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            </div>
        </div>

        @if ($userRole === 'admin')
            @include('dashboard.partials.admin')
        @elseif($userRole === 'waiter')
            @include('dashboard.partials.waiter')
        @elseif($userRole === 'kitchen')
            @include('dashboard.partials.kitchen')
        @elseif($userRole === 'cashier')
            @include('dashboard.partials.cashier')
        @endif
    </div>
</x-app-layout>
