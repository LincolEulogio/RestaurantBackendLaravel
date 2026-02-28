<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ strtoupper($invoice_type) }} - {{ $invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            margin-bottom: 30px;
        }
        .restaurant-info {
            float: left;
            width: 60%;
        }
        .invoice-box {
            float: right;
            width: 35%;
            border: 2px solid #000;
            text-align: center;
            padding: 10px;
        }
        .invoice-box h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .clear {
            clear: both;
        }
        .section-title {
            background-color: #f2f2f2;
            padding: 5px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .customer-info table, .totals-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th {
            background-color: #333;
            color: #fff;
            padding: 8px;
            text-align: left;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .text-right {
            text-align: right;
        }
        .totals-container {
            float: right;
            width: 30%;
            margin-top: 20px;
        }
        .totals-container table td {
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .qr-placeholder {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="restaurant-info">
            <h1 style="margin:0; color: #e67e22;">{{ $restaurant_name }}</h1>
            <p style="margin: 5px 0;">
                {{ $restaurant_address }}<br>
                Tel: {{ $restaurant_phone }}<br>
                Email: {{ $restaurant_email }}
            </p>
        </div>
        <div class="invoice-box">
            <p style="margin: 0; font-weight: bold;">R.U.C. {{ $restaurant_ruc }}</p>
            <h2 style="text-transform: uppercase;">{{ $invoice_type }} ELECTRÓNICA</h2>
            <p style="margin: 0; font-weight: bold; font-size: 14px;">{{ $invoice_number }}</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="section-title">DATOS DEL CLIENTE</div>
    <div class="customer-info">
        <table>
            <tr>
                <td width="20%"><strong>CLIENTE:</strong></td>
                <td>{{ $customer_name }}</td>
                <td width="20%"><strong>FECHA:</strong></td>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <td><strong>{{ $document_label }}:</strong></td>
                <td>{{ $document_number }}</td>
                <td><strong>MONEDA:</strong></td>
                <td>SOLES (S/)</td>
            </tr>
            @if($invoice_type === 'factura')
            <tr>
                <td><strong>DIRECCIÓN:</strong></td>
                <td colspan="3">{{ $customer_address }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">CANT.</th>
                <th>DESCRIPCIÓN</th>
                <th width="15%" class="text-right">P. UNIT</th>
                <th width="15%" class="text-right">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->product_name }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-container">
        <table>
            <tr>
                <td><strong>OP. GRAVADA:</strong></td>
                <td class="text-right">S/ {{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>I.G.V. (18%):</strong></td>
                <td class="text-right">S/ {{ number_format($tax, 2) }}</td>
            </tr>
            @if($delivery_fee > 0)
            <tr>
                <td><strong>DELIVERY:</strong></td>
                <td class="text-right">S/ {{ number_format($delivery_fee, 2) }}</td>
            </tr>
            @endif
            <tr style="font-size: 14px; font-weight: bold; border-top: 2px solid #333;">
                <td>TOTAL:</td>
                <td class="text-right">S/ {{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>

    <div class="qr-placeholder">
        <p style="margin-bottom: 5px;">Representación impresa de la {{ $invoice_type }} electrónica.</p>
        <p>Gracias por su preferencia.</p>
    </div>

    <div class="footer">
        {{ $restaurant_name }} - Sistema de Gestión de Restaurantes
    </div>
</body>
</html>
