<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Culqi\Culqi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $culqi;

    public function __construct()
    {
        $this->culqi = new Culqi([
            'api_key' => config('services.culqi.secret_key')
        ]);
    }

    /**
     * Process a card payment using a Culqi token.
     */
    public function processCardPayment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        $order = Order::findOrFail($request->order_id);

        try {
            // Create a charge in Culqi
            $charge = $this->culqi->Charges->create([
                "amount" => $order->total * 100, // Amount in cents
                "currency_code" => "PEN",
                "email" => $request->email,
                "source_id" => $request->token,
                "description" => "Pago de pedido #" . $order->order_number,
                "antifraud_details" => [
                    "first_name" => $order->customer_name,
                    "last_name" => $order->customer_lastname ?? 'N/A',
                    "phone_number" => $order->customer_phone ?? '999999999'
                ],
                "metadata" => [
                    "order_id" => $order->id,
                    "order_number" => $order->order_number
                ]
            ]);

            if (isset($charge->id)) {
                $order->update([
                    'culqi_charge_id' => $charge->id,
                    'payment_status' => 'paid',
                    'status' => 'confirmed' // Or specific paid status
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Pago procesado correctamente',
                    'charge_id' => $charge->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo procesar el pago'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Culqi Charge Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a Culqi Order for Yape/Plin/PagoEfectivo.
     */
    public function createCulqiOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);

        try {
            // Create a Culqi Order
            $culqiOrder = $this->culqi->Orders->create([
                "amount" => $order->total * 100,
                "currency_code" => "PEN",
                "description" => "Pedido #" . $order->order_number,
                "order_number" => $order->order_number,
                "client_details" => [
                    "first_name" => $order->customer_name,
                    "last_name" => $order->customer_lastname ?? 'N/A',
                    "email" => $order->customer_email ?? 'customer@example.com',
                    "phone_number" => $order->customer_phone ?? '999999999'
                ],
                "expiration_date" => time() + (24 * 60 * 60), // 24 hours
                "confirm" => false
            ]);

            if (isset($culqiOrder->id)) {
                $order->update([
                    'culqi_order_id' => $culqiOrder->id,
                    'payment_status' => 'pending'
                ]);

                return response()->json([
                    'success' => true,
                    'order_id' => $culqiOrder->id
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear la orden de pago'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Culqi Order Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook for payment status updates.
     */
    public function webhook(Request $request)
    {
        // Culqi usually sends a POST request with the event data
        $data = $request->all();
        
        Log::info('Culqi Webhook Received', $data);

        // Verification logic (secret key check) should be here
        
        $object = $data['data'] ?? null;
        if (!$object) return response()->json(['status' => 'error'], 400);

        if ($data['type'] === 'order.status.changed') {
            if ($object['state'] === 'paid') {
                $orderId = $object['metadata']['order_id'] ?? null;
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        $order->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed'
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
