<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $paymentMethods = [
            [
                'name' => 'Efectivo',
                'description' => 'Pago en efectivo',
                'type' => 'cash',
                'is_active' => true,
            ],
            [
                'name' => 'Tarjeta',
                'description' => 'Débito y crédito',
                'type' => 'card',
                'is_active' => true,
            ],
            [
                'name' => 'Transferencia Bancaria',
                'description' => 'Transferencia bancaria',
                'type' => 'transfer',
                'is_active' => true,
                'details' => [
                    'bank' => 'BCP',
                    'account_number' => '',
                ],
            ],
            [
                'name' => 'Yape / Plin',
                'description' => 'Pagos digitales',
                'type' => 'digital',
                'is_active' => true,
                'details' => [
                    'phone' => '',
                ],
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }
    }
}
