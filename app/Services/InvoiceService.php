<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceSeries;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceService
{
    protected NubefactService $nubefactService;

    public function __construct(NubefactService $nubefactService)
    {
        $this->nubefactService = $nubefactService;
    }

    /**
     * Crear factura desde un pedido
     */
    public function createFromOrder(Order $order, string $tipo = 'boleta'): Invoice
    {
        // Validar que el pedido puede ser facturado
        $this->validateOrderForInvoicing($order);

        // Determinar tipo de documento según cliente (Order no tiene relación customer; usar datos del pedido)
        $tipo = $this->determineInvoiceTypeFromOrder($order, $tipo);

        DB::beginTransaction();
        try {
            // Obtener serie activa
            $serie = InvoiceSeries::getDefaultForTipo($tipo);
            if (!$serie) {
                throw new Exception("No hay serie activa configurada para {$tipo}");
            }

            // Generar correlativo
            $correlativo = $serie->getNextCorrelativo();
            $numeroCompleto = $serie->generateNumeroCompleto($correlativo);

            // Crear factura
            $invoice = Invoice::create([
                'tipo' => $tipo,
                'serie' => $serie->serie,
                'correlativo' => $correlativo,
                'numero_completo' => $numeroCompleto,
                'customer_id' => $this->resolveCustomerIdFromOrder($order),
                'order_id' => $order->id,
                'subtotal' => $order->subtotal,
                'igv' => $order->tax,
                'total' => $order->total,
                'moneda' => 'PEN',
                'estado' => 'pendiente',
            ]);

            // Crear items de la factura
            $this->createInvoiceItemsFromOrder($invoice, $order);

            DB::commit();

            // Emitir de forma asíncrona
            \App\Jobs\InvoiceEmissionJob::dispatch($invoice)->onQueue('invoices');

            return $invoice;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creando factura', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Crear items de factura desde items del pedido
     */
    protected function createInvoiceItemsFromOrder(Invoice $invoice, Order $order): void
    {
        foreach ($order->items as $orderItem) {
            $subtotal = (float) $orderItem->subtotal;
            $igv = round($subtotal * 0.18, 2);
            $total = $subtotal + $igv;

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'codigo' => $orderItem->product?->id,
                'descripcion' => $orderItem->product_name,
                'unidad_medida' => 'NIU',
                'cantidad' => $orderItem->quantity,
                'precio_unitario' => $orderItem->unit_price,
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'tipo_igv' => 'gravado',
            ]);
        }
    }

    /**
     * Emitir factura a SUNAT
     */
    public function emitirFactura(Invoice $invoice): bool
    {
        try {
            if ($invoice->estado !== 'pendiente') {
                throw new Exception("La factura no está en estado pendiente");
            }

            // Preparar datos para Nubefact
            $nubefactData = $this->prepareNubefactData($invoice);

            // Enviar a Nubefact
            $response = $this->nubefactService->emitirComprobante($nubefactData);

            if ($response['success']) {
                $invoice->marcarComoEmitida(
                    $response['data']['hash'] ?? null,
                    $response['data']
                );

                // Guardar archivos si están disponibles
                if (isset($response['data']['enlace_del_xml'])) {
                    $invoice->xml_path = $this->downloadAndStoreFile(
                        $response['data']['enlace_del_xml'],
                        "xml/{$invoice->numero_completo}.xml"
                    );
                }

                if (isset($response['data']['enlace_del_pdf'])) {
                    $invoice->pdf_path = $this->downloadAndStoreFile(
                        $response['data']['enlace_del_pdf'],
                        "pdf/{$invoice->numero_completo}.pdf"
                    );
                }

                if (isset($response['data']['enlace_del_cdr'])) {
                    $invoice->cdr_path = $this->downloadAndStoreFile(
                        $response['data']['enlace_del_cdr'],
                        "cdr/{$invoice->numero_completo}.zip"
                    );
                }

                $invoice->save();

                Log::info('Factura emitida exitosamente', [
                    'invoice_id' => $invoice->id,
                    'numero' => $invoice->numero_completo
                ]);

                return true;
            } else {
                $error = $response['error'] ?? 'Error desconocido en SUNAT';
                $invoice->marcarError($error);

                Log::error('Error emitiendo factura', [
                    'invoice_id' => $invoice->id,
                    'error' => $error
                ]);

                return false;
            }

        } catch (Exception $e) {
            $invoice->marcarError($e->getMessage());

            Log::error('Error emitiendo factura', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Preparar datos para enviar a Nubefact
     */
    protected function prepareNubefactData(Invoice $invoice): array
    {
        $customer = $invoice->customer;
        $order = $invoice->order;

        $tipoDoc = $customer ? ($customer->tipo_documento ?? 'DNI') : 'DNI';
        $numDoc = $customer ? ($customer->numero_documento_formateado ?? '') : ($order?->customer_dni ?? '00000000');
        $denom = $customer ? $customer->denominacion_sunat : trim(($order?->customer_name ?? '') . ' ' . ($order?->customer_lastname ?? ''));
        $direccion = $customer ? $customer->direccion_sunat : ($order?->delivery_address ?? '');
        $email = $customer ? ($customer->customer_email ?? '') : ($order?->customer_email ?? '');

        if (empty($denom)) {
            $denom = 'Cliente';
        }
        if (empty($numDoc) || strlen($numDoc) < 8) {
            $numDoc = '00000000';
        }

        $data = [
            "operacion" => "generar_comprobante",
            "tipo_de_comprobante" => $invoice->tipo === 'boleta' ? 2 : 1,
            "serie" => $invoice->serie,
            "numero" => $invoice->correlativo,
            "sunat_transaction" => 1,
            "cliente_tipo_de_documento" => $this->mapTipoDocumento($tipoDoc),
            "cliente_numero_de_documento" => $numDoc,
            "cliente_denominacion" => $denom,
            "cliente_direccion" => $direccion,
            "cliente_email" => $email,
            "cliente_email_1" => "",
            "cliente_email_2" => "",
            "fecha_de_emision" => now()->format('d-m-Y'),
            "fecha_de_vencimiento" => now()->format('d-m-Y'),
            "moneda" => $invoice->moneda,
            "tipo_de_cambio" => "",
            "porcentaje_de_igv" => 18.00,
            "descuento_global" => "",
            "total_descuento" => "",
            "total_anticipo" => "",
            "total_gravada" => $invoice->subtotal,
            "total_inafecta" => "",
            "total_exonerada" => "",
            "total_igv" => $invoice->igv,
            "total_gratuita" => "",
            "total_otros_cargos" => "",
            "total" => $invoice->total,
            "percepcion_tipo" => "",
            "percepcion_base_imponible" => "",
            "total_percepcion" => "",
            "total_incluido_percepcion" => "",
            "detraccion" => "false",
            "observaciones" => $order ? "Pedido #{$order->order_number}" : '',
            "documento_que_se_modifica_tipo" => "",
            "documento_que_se_modifica_serie" => "",
            "documento_que_se_modifica_numero" => "",
            "tipo_de_nota_de_credito" => "",
            "tipo_de_nota_de_debito" => "",
            "enviar_automaticamente_a_la_sunat" => "true",
            "enviar_automaticamente_al_cliente" => "false",
            "codigo_unico" => "",
            "condiciones_de_pago" => "",
            "medio_de_pago" => "",
            "placa_vehiculo" => "",
            "orden_compra_servicio" => $order ? $order->order_number : '',
            "tabla_personalizada_codigo" => "",
            "formato_de_pdf" => "",
            "items" => []
        ];

        // Agregar items
        foreach ($invoice->items as $item) {
            $data['items'][] = [
                "unidad_de_medida" => $item->unidad_medida,
                "codigo" => $item->codigo ?? "",
                "descripcion" => $item->descripcion,
                "cantidad" => $item->cantidad,
                "valor_unitario" => $item->precio_unitario,
                "precio_unitario" => $item->precio_unitario * 1.18, // Incluye IGV
                "descuento" => "",
                "subtotal" => $item->subtotal,
                "tipo_de_igv" => 1, // Gravado - Operación Onerosa
                "igv" => $item->igv,
                "total" => $item->total,
                "anticipo_regularizacion" => "false",
                "anticipo_documento_serie" => "",
                "anticipo_documento_numero" => ""
            ];
        }

        return $data;
    }

    /**
     * Mapear tipo de documento para Nubefact
     */
    protected function mapTipoDocumento(string $tipo): int
    {
        return match($tipo) {
            'DNI' => 1, // DNI
            'RUC' => 6, // RUC
            default => 1
        };
    }

    /**
     * Validar que un pedido puede ser facturado
     */
    protected function validateOrderForInvoicing(Order $order): void
    {
        if (!in_array($order->status, ['delivered', 'ready'])) {
            throw new Exception("El pedido debe estar entregado o listo para ser facturado");
        }

        if ($order->payment_status !== 'paid') {
            throw new Exception("El pedido debe estar pagado para ser facturado");
        }

        // Verificar que no existe ya una factura para este pedido
        $existingInvoice = Invoice::where('order_id', $order->id)
            ->whereIn('estado', ['emitida', 'pendiente'])
            ->first();

        if ($existingInvoice) {
            throw new Exception("Ya existe una factura para este pedido");
        }
    }

    /**
     * Determinar tipo de documento según pedido (Order no tiene relación customer)
     */
    protected function determineInvoiceTypeFromOrder(Order $order, string $tipoSolicitado): string
    {
        $customer = $order->customer_id ? Customer::find($order->customer_id) : null;
        if ($customer) {
            if ($customer->tipo_documento === 'RUC') {
                return 'factura';
            }
            if ($customer->tipo_documento === 'DNI' && $tipoSolicitado === 'boleta') {
                return 'boleta';
            }
            return $tipoSolicitado;
        }

        $dni = $order->customer_dni ?? '';
        if (strlen($dni) === 11 && ctype_digit($dni)) {
            return 'factura';
        }
        return 'boleta';
    }

    /**
     * Resolver customer_id desde pedido (buscar por email/dni o null)
     */
    protected function resolveCustomerIdFromOrder(Order $order): ?int
    {
        if ($order->customer_email) {
            $c = Customer::where('customer_email', $order->customer_email)->first();
            if ($c) {
                return $c->id;
            }
        }
        if ($order->customer_dni) {
            $c = Customer::where('numero_documento', $order->customer_dni)
                ->orWhere('customer_dni', $order->customer_dni)
                ->first();
            if ($c) {
                return $c->id;
            }
        }
        return null;
    }

    /**
     * Descargar y guardar archivo desde URL
     */
    protected function downloadAndStoreFile(string $url, string $path): string
    {
        // TODO: Implementar descarga y almacenamiento de archivos
        // Por ahora solo retornamos la URL
        return $url;
    }

    /**
     * Anular factura
     */
    public function anularFactura(Invoice $invoice, string $motivo): bool
    {
        if (!$invoice->puedeSerAnulada()) {
            throw new Exception("La factura no puede ser anulada");
        }

        // TODO: Implementar nota de crédito en SUNAT
        // Por ahora solo marcar como anulada localmente

        $invoice->anular();

        Log::info('Factura anulada', [
            'invoice_id' => $invoice->id,
            'motivo' => $motivo
        ]);

        return true;
    }

    /**
     * Reemitir factura con error
     */
    public function reemitirFactura(Invoice $invoice): bool
    {
        if (!$invoice->puedeSerReemitida()) {
            throw new Exception("La factura no puede ser reemitida");
        }

        $invoice->estado = 'pendiente';
        $invoice->sunat_error = null;
        $invoice->save();

        return $this->emitirFactura($invoice);
    }
}