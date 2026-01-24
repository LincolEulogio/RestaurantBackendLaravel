<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCustomersFromOrders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'customers:sync';

    /**
     * The console command description.
     */
    protected $description = 'Sync customers from existing orders';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Syncing customers from orders...');

        // Get all unique customer combinations from orders
        $orders = Order::whereNotNull('customer_email')
            ->orWhereNotNull('customer_phone')
            ->orWhereNotNull('customer_dni')
            ->get();

        $customersData = [];

        foreach ($orders as $order) {
            // Create a unique key for each customer
            $key = $order->customer_email ?: $order->customer_phone ?: $order->customer_dni;
            
            if (!$key) {
                continue;
            }

            if (!isset($customersData[$key])) {
                $customersData[$key] = [
                    'customer_name' => $order->customer_name,
                    'customer_lastname' => $order->customer_lastname,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'customer_dni' => $order->customer_dni,
                    'delivery_address' => $order->delivery_address,
                ];
            }
        }

        $this->info('Found ' . count($customersData) . ' unique customers');

        $bar = $this->output->createProgressBar(count($customersData));
        $bar->start();

        foreach ($customersData as $data) {
            // Create or update customer
            $customer = Customer::firstOrCreate(
                [
                    'customer_email' => $data['customer_email'],
                ],
                $data
            );

            // If customer exists but phone/dni changed, update
            if (!$customer->wasRecentlyCreated) {
                $customer->update($data);
            }

            // Update statistics
            $customer->updateStats();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('✓ Customers synced successfully!');

        return Command::SUCCESS;
    }
}
