<!-- 1. KPIs Row (Existing) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Ventas del día -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                <i data-lucide="circle-dollar-sign" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $salesChange >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' }}">
                {{ $salesChange >= 0 ? '+' : '' }}{{ number_format($salesChange, 1) }}%
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas del día</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($todaySales) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $todayOrders }} órdenes</p>
        </div>
    </div>

    <!-- Pedidos Activos -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-orange-600 dark:text-orange-400">
                <i data-lucide="clock" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                {{ $kitchenOrders }} en cocina
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pedidos activos</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $activeOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">En proceso</p>
        </div>
    </div>

    <!-- Mesas Ocupadas -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400">
                <i data-lucide="armchair" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                {{ number_format($tableOccupancy, 0) }}%
            </span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mesas</h3>
            <div class="flex items-baseline gap-1">
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $occupiedTables }}</p>
                <p class="text-sm text-gray-400">de {{ $totalTables }} ocupadas</p>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $tableOccupancy }}%"></div>
            </div>
        </div>
    </div>

    <!-- Stock / Inventario -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-600 dark:text-red-400">
                <i data-lucide="package" class="w-8 h-8"></i>
            </div>
            @if ($lowStockCount > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Crítico</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">OK</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Bajo</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $lowStockCount }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Productos < 10</p>
        </div>
    </div>
</div>

{{-- Eficiencia Operativa --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Rotación de Mesas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                <i data-lucide="rotate-cw" class="w-8 h-8"></i>
            </div>
            @if ($tableRotation > 3)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Excelente</span>
            @elseif($tableRotation < 2)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Mejorar</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">Normal</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rotación de Mesas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($tableRotation, 1) }}x</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Veces por día</p>
        </div>
    </div>

    {{-- Tiempo Promedio de Servicio --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400">
                <i data-lucide="timer" class="w-8 h-8"></i>
            </div>
            @if ($avgServiceTime < 30)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Rápido</span>
            @elseif($avgServiceTime > 45)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Lento</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">Normal</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tiempo de Servicio</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($avgServiceTime, 0) }} min
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Creación → Entrega</p>
        </div>
    </div>

    {{-- Tasa de Cancelación --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-600 dark:text-red-400">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </div>
            @if ($cancellationRate < 5)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Bajo</span>
            @elseif($cancellationRate > 10)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Alto</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Normal</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tasa de Cancelación</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($cancellationRate, 1) }}%</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">De órdenes hoy</p>
        </div>
    </div>

    {{-- Eficiencia de Cocina --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400">
                <i data-lucide="chef-hat" class="w-8 h-8"></i>
            </div>
            @if ($kitchenEfficiency > 5)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Eficiente</span>
            @elseif($kitchenEfficiency < 3)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Mejorar</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">Normal</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Eficiencia Cocina</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($kitchenEfficiency, 1) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Órdenes por hora</p>
        </div>
    </div>
</div>

{{-- Análisis de Clientes, Proyecciones y Calidad --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Clientes Nuevos --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg text-indigo-600 dark:text-indigo-400">
                <i data-lucide="user-plus" class="w-8 h-8"></i>
            </div>
            @if ($newCustomersToday > 5)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Excelente</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Clientes Nuevos</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $newCustomersToday }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Primera orden hoy</p>
        </div>
    </div>

    {{-- Clientes Recurrentes --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400">
                <i data-lucide="repeat" class="w-8 h-8"></i>
            </div>
            @if ($satisfactionRate > 50)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ number_format($satisfactionRate, 0) }}%</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Clientes Recurrentes</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $returningCustomers }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Con más de 1 orden</p>
        </div>
    </div>

    {{-- Proyección Mensual --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg text-emerald-600 dark:text-emerald-400">
                <i data-lucide="trending-up" class="w-8 h-8"></i>
            </div>
            @if ($projectedMonthlySales > $lastMonthSales && $lastMonthSales > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                    +{{ number_format((($projectedMonthlySales - $lastMonthSales) / $lastMonthSales) * 100, 0) }}%
                </span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Proyección Mensual</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($projectedMonthlySales) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Basado en promedio diario</p>
        </div>
    </div>

    {{-- Satisfacción del Cliente --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-yellow-600 dark:text-yellow-400">
                <i data-lucide="heart" class="w-8 h-8"></i>
            </div>
            @if ($satisfactionRate > 60)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Excelente</span>
            @elseif($satisfactionRate > 40)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Bueno</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Satisfacción</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($satisfactionRate, 1) }}%</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Clientes que regresan</p>
        </div>
    </div>
</div>

{{-- Segunda fila de métricas --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Horario Pico --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-orange-600 dark:text-orange-400">
                <i data-lucide="clock-3" class="w-8 h-8"></i>
            </div>
            @if ($peakHourOrders > 10)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">{{ $peakHourOrders }}
                    órdenes</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Horario Pico</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $peakHourTime }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hora con más órdenes</p>
        </div>
    </div>

    {{-- Órdenes Perfectas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-teal-50 dark:bg-teal-900/20 rounded-lg text-teal-600 dark:text-teal-400">
                <i data-lucide="zap" class="w-8 h-8"></i>
            </div>
            @if ($perfectOrderRate > 70)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">{{ number_format($perfectOrderRate, 0) }}%</span>
            @elseif($perfectOrderRate < 40)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">{{ number_format($perfectOrderRate, 0) }}%</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-300">{{ number_format($perfectOrderRate, 0) }}%</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Órdenes Perfectas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $perfectOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Entregadas en &lt;30 min</p>
        </div>
    </div>

    {{-- Ganancia Estimada --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-blue-600 dark:text-blue-400">
                <i data-lucide="wallet" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">60%
                margen</span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ganancia Estimada</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($estimatedProfit) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">De ventas hoy</p>
        </div>
    </div>

    {{-- Predicción Mañana --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg text-purple-600 dark:text-purple-400">
                <i data-lucide="calendar-clock" class="w-8 h-8"></i>
            </div>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">Predicción</span>
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas Mañana</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatMoney($predictedTomorrowSales) }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Basado en semana pasada</p>
        </div>
    </div>
</div>

<!-- Estado de Órdenes -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    @php
        $statsMap = $orderStatusStats->pluck('count', 'status')->toArray();
        $pending = $statsMap['pending'] ?? 0;
        // Usar $kitchenOrders directamente para consistencia con /kitchen
        $ready = $statsMap['ready'] ?? 0;
        $cancelled = $statsMap['cancelled'] ?? 0;
    @endphp

    {{-- Órdenes Pendientes --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg text-yellow-600 dark:text-yellow-400">
                <i data-lucide="clipboard-list" class="w-8 h-8"></i>
            </div>
            @if ($pending > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Atención</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">OK</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Órdenes Pendientes</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $pending }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Por confirmar</p>
        </div>
    </div>

    {{-- Órdenes en Cocina --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-orange-50 dark:bg-orange-900/20 rounded-lg text-orange-600 dark:text-orange-400">
                <i data-lucide="chef-hat" class="w-8 h-8"></i>
            </div>
            @if ($kitchenOrders > 5)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">Alta
                    carga</span>
            @elseif($kitchenOrders > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">En
                    proceso</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Libre</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">En Cocina</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $kitchenOrders }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preparándose</p>
        </div>
    </div>

    {{-- Órdenes Listas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg text-green-600 dark:text-green-400">
                <i data-lucide="check-circle" class="w-8 h-8"></i>
            </div>
            @if ($ready > 3)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Entregar</span>
            @elseif($ready > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Listas</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300">Ninguna</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Órdenes Listas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $ready }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Para entregar</p>
        </div>
    </div>

    {{-- Órdenes Canceladas --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-start">
            <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-600 dark:text-red-400">
                <i data-lucide="x-circle" class="w-8 h-8"></i>
            </div>
            @if ($cancelled > 2)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Alto</span>
            @elseif($cancelled > 0)
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">Algunas</span>
            @else
                <span
                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">Ninguna</span>
            @endif
        </div>
        <div class="mt-4">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Órdenes Canceladas</h3>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $cancelled }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hoy</p>
        </div>
    </div>
</div>

<!-- 3. Weekly Sales & AI Insights -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Weekly Sales Chart -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 lg:col-span-2">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold text-gray-800 dark:text-gray-100" id="salesChartTitle">Ventas Semanales</h3>
                <span class="text-sm text-gray-500 dark:text-gray-400" id="salesChartSubtitle">Últimos 7 días</span>
            </div>
            <!-- Time Filter Buttons -->
            <div class="flex gap-2">
                <button data-filter="hour"
                    class="sales-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Hora
                </button>
                <button data-filter="today"
                    class="sales-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Hoy
                </button>
                <button data-filter="week"
                    class="sales-filter-btn active px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 bg-indigo-600 text-white hover:bg-indigo-700">
                    Semana
                </button>
                <button data-filter="month"
                    class="sales-filter-btn px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                    Mes
                </button>
            </div>
        </div>
        <div class="h-64 w-full relative">
            <!-- Loading Spinner -->
            <div id="chartLoadingSpinner"
                class="absolute inset-0 items-center justify-center bg-white/80 dark:bg-gray-800/80 hidden">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>
            <canvas id="weeklySalesChart"></canvas>
        </div>
    </div>

    <!-- AI Insights (Responsive) -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-2 mb-6">
            <i data-lucide="sparkles" class="w-6 h-6 text-yellow-500 dark:text-yellow-400"></i>
            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100">Insights de Hoy</h3>
        </div>

        <div class="space-y-4">
            @forelse($aiInsights as $insight)
                <div
                    class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 border border-gray-100 dark:border-gray-700 flex gap-3 items-start">
                    <div class="p-1.5 bg-white dark:bg-gray-600 rounded-md shrink-0 shadow-sm">
                        <i data-lucide="{{ $insight['icon'] }}"
                            class="w-4 h-4 text-indigo-500 dark:text-indigo-300"></i>
                    </div>
                    <p class="text-sm font-medium leading-tight text-gray-600 dark:text-gray-300">
                        {{ $insight['text'] }}</p>
                </div>
            @empty
                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                    <p>Analizando datos...</p>
                </div>
            @endforelse

            <!-- Daily Reco -->
            @if ($topProducts->count() > 0)
                <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold mb-2">
                        Recomendación del Día</p>
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-800 dark:text-white">{{ $topProducts->first()->name }}</span>
                        <span
                            class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 px-2 py-1 rounded">Promocionar</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- 4. Peak Hours & Payment Methods (NEW) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Peak Hours -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 lg:col-span-2">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6">Horas Pico de Venta</h3>
        <div class="h-60 w-full">
            <canvas id="peakHoursChart"></canvas>
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6">Ingresos por Método de Pago</h3>
        <div class="h-48 w-full relative flex justify-center">
            <canvas id="paymentMethodsChart"></canvas>
        </div>
        <div class="mt-4 space-y-2">
            @foreach ($paymentMethodsToday as $method)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400 capitalize">{{ $method->payment_method }}</span>
                    <span
                        class="font-semibold text-gray-900 dark:text-white">{{ formatMoney($method->revenue) }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- 5. Bottom Row: Top Dishes, Inventory, Top Waiters -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Top Dishes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Platos Más Vendidos</h3>
            <a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Ver
                todo</a>
        </div>
        <div class="space-y-4">
            @forelse($topProducts as $product)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-700 dark:text-gray-200">{{ $product->name }}</span>
                        <span
                            class="text-gray-900 dark:text-white font-semibold">{{ $product->total_quantity }}</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full"
                            style="width: {{ ($product->total_quantity / $maxQuantity) * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center">Sin datos</p>
            @endforelse
        </div>
    </div>

    <!-- Smart Inventory (NEW) -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6">Estado del Inventario</h3>
        <div class="grid grid-cols-3 gap-4 text-center mb-6">
            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
                <p class="text-2xl font-bold text-green-700 dark:text-green-400">{{ $inventoryStats['normal'] }}</p>
                <p class="text-xs text-green-600 dark:text-green-500 uppercase font-bold mt-1">Normal</p>
            </div>
            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">{{ $inventoryStats['low'] }}</p>
                <p class="text-xs text-yellow-600 dark:text-yellow-500 uppercase font-bold mt-1">Bajo</p>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                <p class="text-2xl font-bold text-red-700 dark:text-red-400">{{ $inventoryStats['critical'] }}</p>
                <p class="text-xs text-red-600 dark:text-red-500 uppercase font-bold mt-1">Crítico</p>
            </div>
        </div>
        @if ($inventoryStats['critical'] > 0)
            <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-100 rounded-lg">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <div>
                    <p class="text-sm font-medium text-red-800">Acción requerida</p>
                    <p class="text-xs text-red-600 mt-1">Hay {{ $inventoryStats['critical'] }} productos que requieren
                        reabastecimiento urgente.</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Top Waiters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6">Top Meseros (Hoy)</h3>
        <div class="space-y-4">
            @forelse($topWaiters as $index => $waiter)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span
                            class="w-6 h-6 flex items-center justify-center rounded-full {{ $index == 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600' }} text-xs font-bold">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ Str::limit($waiter->name, 15) }}</p>
                            <p class="text-xs text-gray-500">{{ $waiter->order_count }} órdenes</p>
                        </div>
                    </div>
                    <span
                        class="text-sm font-bold text-gray-900 dark:text-white">{{ formatMoney($waiter->total_sales) }}</span>
                </div>
            @empty
                <p class="text-gray-500 text-center">Sin actividad hoy</p>
            @endforelse
        </div>
    </div>
</div>

<!-- 6. Advanced Efficiency & Quality Metrics (NEW) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Dead Dishes -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 dark:text-gray-100">Platos "Muertos" (30 días)</h3>
            <span class="text-xs text-gray-500 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">Rotación urgente</span>
        </div>
        <div class="space-y-3">
            @forelse($deadDishes as $dish)
                <div
                    class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 rounded-lg transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg text-red-500">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ Str::limit($dish->name, 20) }}</p>
                    </div>
                    <span class="text-xs text-red-500 font-semibold">0 ventas</span>
                </div>
            @empty
                <div class="text-center py-4">
                    <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-500">¡Todo el menú se mueve!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Efficiency KPIs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6">Eficiencia Operativa</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-center">
                <p class="text-xs text-indigo-600 dark:text-indigo-400 uppercase font-bold mb-1">Mesa más usada</p>
                <p class="text-xl font-bold text-indigo-700 dark:text-indigo-300">{{ $mostUsedTableName }}</p>
            </div>
            <div
                class="p-4 {{ $delayedOrdersCount > 0 ? 'bg-red-50 dark:bg-red-900/20' : 'bg-green-50 dark:bg-green-900/20' }} rounded-xl text-center">
                <p
                    class="text-xs {{ $delayedOrdersCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} uppercase font-bold mb-1">
                    Retrasos >40m</p>
                <p
                    class="text-xl font-bold {{ $delayedOrdersCount > 0 ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300' }}">
                    {{ $delayedOrdersCount }}</p>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Ticket Promedio por Canal</h4>
            <div class="space-y-3">
                @forelse($avgTicketByChannel as $channel)
                    <div class="flex justify-between items-center text-sm">
                        <span
                            class="text-gray-600 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $channel->order_type) }}</span>
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500"
                                    style="width: {{ min(($channel->avg_ticket / 100) * 100, 100) }}%"></div>
                            </div>
                            <span
                                class="font-bold text-gray-900 dark:text-white w-16 text-right">{{ formatMoney($channel->avg_ticket) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400 text-center">Sin datos hoy</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- System Health (Operational Summary) -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700 flex flex-col justify-between relative overflow-hidden">
        <div class="relative z-10">
            <h3 class="font-bold text-lg text-gray-800 dark:text-gray-100 mb-2">Resumen Gerencial</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Estado global del restaurante hoy.</p>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Satisfacción (Est.)</span>
                    <div class="flex text-yellow-400 gap-0.5">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star-half" class="w-4 h-4 fill-current"></i>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Carga Cocina</span>
                    <span
                        class="text-sm font-bold {{ $kitchenOrders > 5 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400' }}">
                        {{ $kitchenOrders > 5 ? 'Alta' : 'Normal' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Total Descuentos</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">S/ 0.00</span>
                </div>
            </div>
        </div>
        <!-- Decorative bg kept but subtle -->
        <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-3xl">
        </div>
    </div>
</div>

{{-- Productividad de Personal y Restock --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Productividad de Meseros --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
            <i data-lucide="users-2" class="w-5 h-5 text-blue-600"></i>
            Productividad de Meseros (Hoy)
        </h3>
        <div class="space-y-3">
            @forelse($waiterProductivity as $waiter)
                <div
                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $waiter->name }}</p>
                        <div class="flex gap-4 mt-1">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <i data-lucide="shopping-cart" class="w-3 h-3 inline"></i>
                                {{ $waiter->total_orders }} órdenes
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <i data-lucide="check-circle" class="w-3 h-3 inline"></i>
                                {{ $waiter->completed_orders }} completadas
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900 dark:text-white">
                            {{ formatMoney($waiter->total_sales) }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Ticket:
                            {{ formatMoney($waiter->avg_ticket) }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 py-4">Sin datos de meseros hoy</p>
            @endforelse
        </div>
    </div>

    {{-- Productos que Necesitan Restock --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <h3 class="font-bold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
            <i data-lucide="package-x" class="w-5 h-5 text-red-600"></i>
            Productos que Necesitan Restock
        </h3>
        <div class="space-y-2">
            @forelse($productsNeedingRestock as $item)
                <div
                    class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-100 dark:border-red-900/30">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600 dark:text-red-400"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ Str::limit($item->name, 25) }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Mín: {{ $item->stock_min }}
                                {{ $item->unit }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p
                            class="text-lg font-bold {{ $item->stock_current < $item->stock_min ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                            {{ $item->stock_current }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item->unit }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <i data-lucide="check-circle" class="w-12 h-12 text-green-500 mx-auto mb-2"></i>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Todo el inventario está bien abastecido</p>
                </div>
            @endforelse
        </div>
        @if ($productsNeedingRestock->count() > 0)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('inventory.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                    Ver inventario completo
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        window.dashboardData = {
            weeklySales: @json($weeklyData),
            hourlySales: @json($hourlySales),
            paymentMethods: @json($paymentMethodsToday)
        };

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Weekly Sales Chart - Store reference for dynamic updates
            const weeklyCtx = document.getElementById('weeklySalesChart')?.getContext('2d');
            let weeklySalesChartInstance = null;

            if (weeklyCtx) {
                weeklySalesChartInstance = new Chart(weeklyCtx, {
                    type: 'line',
                    data: {
                        labels: window.dashboardData.weeklySales.map(d => {
                            const date = new Date(d.date);
                            return date.toLocaleDateString('es-ES', {
                                weekday: 'short'
                            });
                        }),
                        datasets: [{
                            label: 'Ventas',
                            data: window.dashboardData.weeklySales.map(d => d.revenue),
                            borderColor: '#6366f1',
                            backgroundColor: (ctx) => {
                                const grad = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                                grad.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
                                grad.addColorStop(1, 'rgba(99, 102, 241, 0)');
                                return grad;
                            },
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#6366f1',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(107, 114, 128, 0.1)'
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            }
                        }
                    }
                });

                // Sales Chart Filter Functionality
                const filterButtons = document.querySelectorAll('.sales-filter-btn');
                const chartTitle = document.getElementById('salesChartTitle');
                const chartSubtitle = document.getElementById('salesChartSubtitle');
                const loadingSpinner = document.getElementById('chartLoadingSpinner');

                filterButtons.forEach(btn => {
                    btn.addEventListener('click', async function() {
                        if (this.disabled) return;

                        // Update active state
                        filterButtons.forEach(b => {
                            b.classList.remove('active', 'bg-indigo-600', 'text-white',
                                'hover:bg-indigo-700');
                            b.classList.add('bg-gray-100', 'dark:bg-gray-700',
                                'text-gray-700', 'dark:text-gray-300');
                        });
                        this.classList.add('active', 'bg-indigo-600', 'text-white',
                            'hover:bg-indigo-700');
                        this.classList.remove('bg-gray-100', 'dark:bg-gray-700',
                            'text-gray-700', 'dark:text-gray-300');

                        const filter = this.dataset.filter;
                        loadingSpinner.classList.remove('hidden');
                        loadingSpinner.classList.add('flex');
                        filterButtons.forEach(b => b.disabled = true);

                        try {
                            const response = await fetch(
                                `/dashboard/filter-sales?timeFilter=${filter}`);
                            const data = await response.json();

                            weeklySalesChartInstance.data.labels = data.labels;
                            weeklySalesChartInstance.data.datasets[0].data = data.data;
                            weeklySalesChartInstance.update('active');

                            const titles = {
                                'hour': {
                                    title: 'Ventas por Hora',
                                    subtitle: 'Última hora'
                                },
                                'today': {
                                    title: 'Ventas de Hoy',
                                    subtitle: 'Últimas 24 horas'
                                },
                                'week': {
                                    title: 'Ventas Semanales',
                                    subtitle: 'Últimos 7 días'
                                },
                                'month': {
                                    title: 'Ventas Mensuales',
                                    subtitle: 'Últimos 30 días'
                                }
                            };
                            chartTitle.textContent = titles[filter].title;
                            chartSubtitle.textContent = titles[filter].subtitle;

                        } catch (error) {
                            console.error('Error fetching sales data:', error);
                        } finally {
                            loadingSpinner.classList.add('hidden');
                            loadingSpinner.classList.remove('flex');
                            filterButtons.forEach(b => b.disabled = false);
                        }
                    });
                });
            }

            // 2. Peak Hours Chart (Bar)
            const peakCtx = document.getElementById('peakHoursChart')?.getContext('2d');
            if (peakCtx) {
                // Fill 0-23 hours
                const hours = Array.from({
                    length: 24
                }, (_, i) => i);
                const salesByHour = window.dashboardData.hourlySales.reduce((acc, curr) => {
                    acc[curr.hour] = curr.revenue;
                    return acc;
                }, {});
                const data = hours.map(h => salesByHour[h] || 0);

                new Chart(peakCtx, {
                    type: 'bar',
                    data: {
                        labels: hours.map(h => h + ':00'),
                        datasets: [{
                            label: 'Ventas',
                            data: data,
                            backgroundColor: '#f59e0b', // Amber 500
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#1f2937'
                            }
                        },
                        scales: {
                            y: {
                                display: false
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // 3. Payment Methods (Doughnut)
            const payCtx = document.getElementById('paymentMethodsChart')?.getContext('2d');
            if (payCtx) {
                const labels = window.dashboardData.paymentMethods.map(p => p.payment_method);
                const data = window.dashboardData.paymentMethods.map(p => p.revenue);

                new Chart(payCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: ['#10b981', '#3b82f6', '#8b5cf6', '#ef4444'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        }, // Custom legend used in HTML
                        cutout: '75%'
                    }
                });
            }
        });
    </script>
@endpush
