<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * POST /api/payment/process-card
     * Matching useCartSidebar flow
     */
    public function processCardPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'token' => 'required|string',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($request->order_id);
        
        $result = $this->paymentService->processPayment($order, [
            'token' => $request->token,
            'email' => $request->email
        ]);

        return response()->json($result);
    }

    /**
     * POST /api/payment/create-order
     * Simulates creating a Culqi Order for Yape/Plin
     */
    public function createCulqiOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        return response()->json([
            'success' => true,
            'order_id' => 'ord_test_' . uniqid(), // Dummy Culqi Order ID
            'message' => 'Simulación de orden de pago Culqi'
        ]);
    }

    /**
     * POST /api/payment/process-manual
     * New endpoint for direct operation number submission (Yape/Plin)
     */
    public function processManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'operation_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($request->order_id);
        
        $result = $this->paymentService->processPayment($order, [
            'operation_number' => $request->operation_number
        ]);

        return response()->json($result);
    }
}
