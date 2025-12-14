<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:cash,card,transfer,digital',
            'is_active' => 'boolean',
            'details' => 'nullable|array',
            'image' => 'nullable|string',
        ]);

        $paymentMethod = PaymentMethod::create($validated);

        return back()->with('success', 'Método de pago creado correctamente.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:cash,card,transfer,digital',
            'is_active' => 'boolean',
            'details' => 'nullable|array',
            'image' => 'nullable|string',
        ]);

        $paymentMethod->update($validated);

        return back()->with('success', 'Método de pago actualizado correctamente.');
    }

    public function toggle(Request $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $paymentMethod->is_active,
                'message' => 'Estado actualizado correctly.'
            ]);
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return back()->with('success', 'Método de pago eliminado correctamente.');
    }
}
