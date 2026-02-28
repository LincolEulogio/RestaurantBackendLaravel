<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; border-bottom: 2px solid #f8fafc; padding-bottom: 20px; margin-bottom: 20px; }
        .footer { text-align: center; color: #94a3b8; font-size: 12px; margin-top: 30px; }
        .btn { background: #0f172a; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>¡Hola, {{ $invoice->customer_name }}!</h2>
            <p>Gracias por tu compra en nuestro restaurante.</p>
        </div>
        
        <p>Adjunto a este correo encontrarás tu comprobante electrónico ({{ $invoice->invoice_type === 'factura' ? 'Factura' : 'Boleta' }}) número <strong>{{ $invoice->invoice_number }}</strong>.</p>
        
        <div style="background: #f8fafc; padding: 20px; border-radius: 10px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px; color: #64748b;">Monto Total Pagado:</p>
            <p style="margin: 0; font-size: 24px; font-weight: 900; color: #0f172a;">S/ {{ number_format($invoice->total, 2) }}</p>
        </div>

        <p>Si tienes alguna duda o problema con tu pedido, no dudes en contactarnos respondiendo a este correo.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
