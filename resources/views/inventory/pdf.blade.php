<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #1a56db;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 10px 5px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        td {
            padding: 10px 5px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }

        .status-ok {
            background-color: #def7ec;
            color: #03543f;
        }

        .status-low {
            background-color: #fde8e8;
            color: #9b1c1c;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #999;
            padding: 10px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">RestaurantOS</div>
        <div class="subtitle">Reporte de Inventario y Stock</div>
        <div style="margin-top: 10px; font-size: 10px; color: #999;">
            Generado el: {{ now()->format('d/m/Y h:i A') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="25%">Insumo</th>
                <th width="15%">Categoría</th>
                <th width="10%" class="text-center">Actual</th>
                <th width="10%" class="text-center">Mínimo</th>
                <th width="10%" class="text-center">Unidad</th>
                <th width="15%" class="text-right">Precio Unit.</th>
                <th width="10%" class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td class="text-center">{{ $item->id }}</td>
                    <td>
                        <div class="font-bold">{{ $item->name }}</div>
                        <div style="font-size: 9px; color: #666;">SKU: {{ $item->sku }}</div>
                    </td>
                    <td>{{ $item->category ?? 'N/A' }}</td>
                    <td class="text-center font-bold">{{ number_format($item->stock_current, 2) }}</td>
                    <td class="text-center">{{ number_format($item->stock_min, 2) }}</td>
                    <td class="text-center">{{ $item->unit }}</td>
                    <td class="text-right">S/ {{ number_format($item->price_unit, 2) }}</td>
                    <td class="text-center">
                        @if ($item->stock_current <= $item->stock_min)
                            <span class="status-badge status-low">BAJO</span>
                        @else
                            <span class="status-badge status-ok">OK</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Página
        <script type="text/php">if (isset($pdf)) { $x = 280; $y = 820; $text = "{PAGE_NUM} de {PAGE_COUNT}"; $font = $fontMetrics->get_font("helvetica", "bold"); $size = 8; $color = array(0,0,0); $word_space = 0.0; $char_space = 0.0; $angle = 0.0; $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle); }</script>
    </div>
</body>

</html>
