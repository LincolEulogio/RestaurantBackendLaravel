<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Reporte de Ventas - RestaurantOS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a56db;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #1a56db;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 30px 0 15px;
            color: #1e40af;
            border-left: 4px solid #1e40af;
            padding-left: 10px;
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .metric-box {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 15px;
            text-align: center;
            border-radius: 10px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .metric-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: #f3f4f6;
            padding: 12px;
            border-bottom: 2px solid #d1d5db;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: #4b5563;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .metric-box {
                border: 1px solid #ddd;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1a56db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">Imprimir Reporte</button>

    <div class="header">
        <h1>RestaurantOS - Reporte de Ventas</h1>
        <p>Periodo: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} al
            {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">Resumen Ejecutivo</div>
    <div class="metrics-grid">
        <div class="metric-box">
            <div class="metric-value">S/ {{ number_format($totalRevenue, 2) }}</div>
            <div class="metric-label">Ingresos Totales</div>
        </div>
        <div class="metric-box">
            <div class="metric-value">{{ number_format($completedOrders) }}</div>
            <div class="metric-label">Órdenes</div>
        </div>
        <div class="metric-box">
            <div class="metric-value">S/ {{ number_format($averageTicket, 2) }}</div>
            <div class="metric-label">Ticket Promedio</div>
        </div>
        <div class="metric-box">
            <div class="metric-value">S/ {{ number_format($netProfit, 2) }}</div>
            <div class="metric-label">Ganancia Neta (Est.)</div>
        </div>
    </div>

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
                    <td><strong>{{ $product->name }}</strong></td>
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
                <th class="text-center">Ventas</th>
                <th class="text-right">Ingresos</th>
                <th class="text-right">Margen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categoryPerformance as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td class="text-center">{{ number_format($cat->total_sales) }}</td>
                    <td class="text-right">S/ {{ number_format($cat->total_revenue, 2) }}</td>
                    <td class="text-right">{{ $cat->margin }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <div>
            <div class="section-title">Métodos de Pago</div>
            <table>
                <thead>
                    <tr>
                        <th>Método</th>
                        <th class="text-right">Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paymentMethods as $method)
                        <tr>
                            <td style="text-transform: capitalize;">{{ $method->payment_method }}</td>
                            <td class="text-right">S/ {{ number_format($method->revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div>
            <div class="section-title">Tipos de Orden</div>
            <table>
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th class="text-right">Ventas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orderTypes as $type)
                        <tr>
                            <td style="text-transform: capitalize;">{{ $type->order_type }}</td>
                            <td class="text-right">S/ {{ number_format($type->revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div
        style="text-align: center; color: #999; font-size: 10px; margin-top: 40px; border-top: 1px solid #eee; padding-top: 10px;">
        Este documento es un reporte oficial generado por el Sistema de Inteligencia RestaurantOS.
        <br>Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>

</html>
