<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Inventario - RestaurantOS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.5;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            color: #1a56db;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }

        td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .status {
            font-weight: bold;
        }

        .status-low {
            color: #dc2626;
        }

        .status-ok {
            color: #16a34a;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            button {
                display: none;
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
        }
    </style>
</head>

<body>
    <button onclick="window.print()" class="print-button no-print">Imprimir Ahora</button>

    <div class="header">
        <h1>RestaurantOS - Reporte de Inventario</h1>
        <p>Fecha de generación: {{ now()->format('d/m/Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th class="text-center">Stock</th>
                <th class="text-center">Mínimo</th>
                <th class="text-center">Unidad</th>
                <th class="text-right">Precio</th>
                <th class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="text-center">{{ $item->id }}</td>
                    <td>
                        <strong>{{ $item->name }}</strong><br>
                        <small style="color: #666">SKU: {{ $item->sku }}</small>
                    </td>
                    <td>{{ $item->category ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($item->stock_current, 2) }}</td>
                    <td class="text-center">{{ number_format($item->stock_min, 2) }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td class="text-right">S/ {{ number_format($item->price_unit, 2) }}</td>
                    <td class="text-center">
                        @if ($item->stock_current <= $item->stock_min)
                            <span class="status status-low">BAJO STOCK</span>
                        @else
                            <span class="status status-ok">OK</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            // Optional: Uncomment to auto-print
            // window.print();
        }
    </script>
</body>

</html>
