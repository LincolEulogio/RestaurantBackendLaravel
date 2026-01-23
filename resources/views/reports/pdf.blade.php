<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Rendimiento</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a56db;
            padding-bottom: 10px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #1a56db;
        }

        .date {
            color: #666;
            margin-top: 5px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #1e40af;
            border-left: 4px solid #1e40af;
            padding-left: 10px;
        }

        .metrics-grid {
            width: 100%;
            margin-bottom: 20px;
        }

        .metric-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: center;
        }

        .metric-value {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }

        .metric-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background: #f3f4f6;
            padding: 8px;
            border-bottom: 1px solid #d1d5db;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">RestaurantOS - Reporte de Rendimiento</div>
        <div class="date">Periodo: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</div>
    </div>

    <div class="section-title">Resumen Ejecutivo</div>
    <table class="metrics-grid">
        <tr>
            <td class="metric-box" width="25%">
                <div class="metric-value">S/ {{ number_format($totalRevenue, 2) }}</div>
                <div class="metric-label">Ingresos Totales</div>
            </td>
            <td class="metric-box" width="25%">
                <div class="metric-value">{{ number_format($completedOrders) }}</div>
                <div class="metric-label">Órdenes Completadas</div>
            </td>
            <td class="metric-box" width="25%">
                <div class="metric-value">S/ {{ number_format($averageTicket, 2) }}</div>
                <div class="metric-label">Ticket Promedio</div>
            </td>
            <td class="metric-box" width="25%">
                <div class="metric-value">S/ {{ number_format($netProfit, 2) }}</div>
                <div class="metric-label">Ganancia Neta (Est. 20%)</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Top 5 Platillos</div>
    <table>
        <thead>
            <tr>
                <th>Platillo</th>
                <th class="text-center">Cant. Vendida</th>
                <th class="text-right">Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td class="text-center">{{ number_format($product->total_quantity) }}</td>
                    <td class="text-right">S/ {{ number_format($product->total_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Rendimiento por Categoría</div>
    <table>
        <thead>
            <tr>
                <th>Categoría</th>
                <th class="text-right">Ventas</th>
                <th class="text-right">Ingresos</th>
                <th class="text-right">Margen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categoryPerformance as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td class="text-right">{{ number_format($cat->total_sales) }}</td>
                    <td class="text-right">S/ {{ number_format($cat->total_revenue, 2) }}</td>
                    <td class="text-right">{{ $cat->margin }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }} - RestaurantOS Intelligence System
    </div>
</body>

</html>
