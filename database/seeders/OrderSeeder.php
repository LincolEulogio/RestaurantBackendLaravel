<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Table;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $products = Product::query()->where('is_available', true)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No hay productos disponibles para crear pedidos.');
            return;
        }

        $faker = Faker::create('es_PE');
        $customers = Customer::all();
        $tables = Table::all();
        $waiterIds = User::where('role', 'mesero')->pluck('id');

        $orderTypes = ['delivery', 'pickup', 'dine-in'];
        $orderStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'in_transit', 'delivered', 'cancelled', 'paid'];
        $paymentMethods = ['card', 'yape', 'plin', 'cash'];

        for ($i = 0; $i < 35; $i++) {
            $customer = $customers->isNotEmpty() ? $customers->random() : null;
            $orderType = $faker->randomElement($orderTypes);
            $status = $faker->randomElement($orderStatuses);
            $paymentStatus = in_array($status, ['delivered', 'paid'], true)
                ? 'paid'
                : $faker->randomElement(['pending', 'pending', 'failed']);
            $billingType = $faker->boolean(20) ? 'factura' : 'boleta';

            $orderDate = now()
                ->subDays($faker->numberBetween(0, 21))
                ->subMinutes($faker->numberBetween(0, 1200));

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'order_date' => $orderDate,
                'order_type' => $orderType,
                'order_source' => $orderType === 'dine-in'
                    ? $faker->randomElement(['waiter', 'qr'])
                    : 'web',
                'customer_name' => $customer ? $customer->customer_name : $faker->firstName(),
                'customer_lastname' => $customer ? $customer->customer_lastname : $faker->lastName(),
                'customer_email' => $customer ? $customer->customer_email : $faker->safeEmail(),
                'customer_phone' => $customer ? $customer->customer_phone : preg_replace('/\D+/', '', $faker->phoneNumber()),
                'customer_dni' => $customer ? $customer->customer_dni : null,
                'delivery_address' => $orderType === 'delivery'
                    ? ($customer ? $customer->delivery_address : $faker->address())
                    : null,
                'status' => $status,
                'payment_method' => $faker->randomElement($paymentMethods),
                'payment_status' => $paymentStatus,
                'billing_type' => $billingType,
                'business_name' => $billingType === 'factura' ? 'Empresa Demo SAC' : null,
                'ruc' => $billingType === 'factura' ? '20123456789' : null,
                'fiscal_address' => $billingType === 'factura' ? 'Av. Empresarial 123, Lima' : null,
                'notes' => $faker->optional(0.4)->sentence(),
                'subtotal' => 0,
                'tax' => 0,
                'delivery_fee' => 0,
                'total' => 0,
            ]);

            if ($orderType === 'dine-in' && $tables->isNotEmpty()) {
                $order->table_id = $tables->random()->id;
                if ($waiterIds->isNotEmpty()) {
                    $order->waiter_id = $waiterIds->random();
                }
                $order->save();
            }

            $itemsCount = $faker->numberBetween(1, 4);
            $subtotal = 0;

            for ($j = 0; $j < $itemsCount; $j++) {
                $product = $products->random();
                $quantity = $faker->numberBetween(1, 3);
                $unitPrice = (float) $product->price;
                $lineSubtotal = round($quantity * $unitPrice, 2);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineSubtotal,
                    'special_instructions' => $faker->optional(0.3)->sentence(),
                ]);

                $subtotal += $lineSubtotal;
            }

            $deliveryFee = $orderType === 'delivery' ? $faker->randomFloat(2, 3, 12) : 0;
            $tax = round($subtotal * 0.18, 2);
            $total = max(0, round($subtotal + $tax + $deliveryFee, 2));

            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->delivery_fee = $deliveryFee;
            $order->total = $total;

            if ($paymentStatus === 'paid') {
                $order->paid_at = $orderDate->copy()->addMinutes($faker->numberBetween(10, 90));
            }

            if (in_array($status, ['confirmed', 'preparing', 'ready', 'in_transit', 'delivered', 'paid'], true)) {
                $order->confirmed_at = $orderDate->copy()->addMinutes(5);
            }

            if (in_array($status, ['ready', 'in_transit', 'delivered', 'paid'], true)) {
                $order->ready_at = $orderDate->copy()->addMinutes(25);
            }

            if (in_array($status, ['in_transit', 'delivered', 'paid'], true)) {
                $order->in_transit_at = $orderDate->copy()->addMinutes(35);
            }

            if (in_array($status, ['delivered', 'paid'], true)) {
                $order->delivered_at = $orderDate->copy()->addMinutes(55);
            }

            $order->save();

            if ($status !== 'pending') {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $waiterIds->isNotEmpty() ? $waiterIds->random() : null,
                    'from_status' => 'pending',
                    'to_status' => $status,
                    'notes' => 'Estado inicial de demo',
                ]);
            }
        }

        Customer::query()->each(function (Customer $customer) {
            $customer->updateStats();
        });

        $this->command->info('Pedidos y detalles de pedido de demo creados correctamente.');
    }
}
