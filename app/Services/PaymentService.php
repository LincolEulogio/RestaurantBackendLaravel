<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment for an order
     */
    public function processPayment(Order $order, array $data): array
    {
        $method = $order->payment_method;

        try {
            switch ($method) {
                case 'card':
                    return $this->processCardPayment($order, $data);
                case 'yape':
                case 'plin':
                    return $this->processManualPayment($order, $data);
                case 'cash':
                    return $this->processCashPayment($order);
                default:
                    throw new Exception("Método de pago no soportado");
            }
        } catch (Exception $e) {
            Log::error("Payment Error: " . $e->getMessage(), ['order_id' => $order->id]);
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Simulates or processes Culqi Card Payment
     */
    protected function processCardPayment(Order $order, array $data): array
    {
        $token = $data['token'] ?? null;
        
        if (!$token) {
            throw new Exception("Token de pago requerido para tarjeta");
        }

        // Check if we are in Sandbox mode
        $isSandbox = config('services.culqi.mode') === 'sandbox' || config('app.env') !== 'production';

        if ($isSandbox) {
            // SIMULATE SUCCESS
            $invoice = $order->finalizeAndInvoice('SANDBOX-' . uniqid());
            
            return [
                'success' => true,
                'message' => 'Pago simulado con éxito (MODO PRUEBAS)',
                'transaction_id' => 'SANDBOX-' . uniqid(),
                'invoice_number' => $invoice->invoice_number
            ];
        }

        // REAL CULQI INTEGRATION LOGIC WOULD GO HERE
        // For now, let's keep it simulated as requested by user for "desarrollo"
        throw new Exception("Integración de producción no configurada");
    }

    /**
     * Process Yape/Plin (Manual verification)
     */
    protected function processManualPayment(Order $order, array $data): array
    {
        $operationNumber = $data['operation_number'] ?? null;

        if (!$operationNumber) {
            throw new Exception("Número de operación es requerido");
        }

        // Update order with reference
        $order->updatePaymentStatus('pending', $operationNumber); // Marks as pending review
        
        // We can use a custom field if we want, but using updatePaymentStatus reference is fine
        $order->notes = ($order->notes ? $order->notes . "\n" : "") . "Verificar Pago: " . strtoupper($order->payment_method) . " #" . $operationNumber;
        $order->save();

        return [
            'success' => true,
            'message' => 'Información de pago recibida. Su pedido será procesado tras verificación.',
            'operation_number' => $operationNumber
        ];
    }

    /**
     * Process Cash Payment
     */
    protected function processCashPayment(Order $order): array
    {
        $order->updatePaymentStatus('pending', 'CASH');
        return [
            'success' => true,
            'message' => 'Pedido registrado. Pagará en efectivo al recibir su orden.'
        ];
    }
}
