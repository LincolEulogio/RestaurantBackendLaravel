<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Printer;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
// use Mike42\Escpos\PrintConnectors\FilePrintConnector;
// use Mike42\Escpos\Printer as EscposPrinter;
use Illuminate\Support\Facades\Log;

class PrintService
{
    /**
     * Print a receipt for an order.
     */
    public function printOrderTicket(Order $order, $printerName = 'kitchen')
    {
        $printerConfig = Printer::where('name', $printerName)->where('is_active', true)->first();

        if (!$printerConfig) {
            Log::warning("Printer {$printerName} not found or inactive.");
            return false;
        }

        try {
            // In a real environment, you'd use NetworkPrintConnector or similar
            // For testing/mocking, we can log the content
            
            /* 
            $connector = new NetworkPrintConnector($printerConfig->ip_address, $printerConfig->port);
            $printer = new EscposPrinter($connector);
            */

            Log::info("PRINTING TICKET to {$printerName}: Order #{$order->order_number}");
            
            // Layout (Simplified for demonstration)
            $ticket = "--------------------------------\n";
            $ticket .= "       RESTAURANT APP          \n";
            $ticket .= "--------------------------------\n";
            $ticket .= "Pedido: " . ($order->order_number ?? 'N/A') . "\n";
            $ticket .= "Fecha: " . ($order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A') . "\n";
            $ticket .= "Mesa: " . (isset($order->table) && is_object($order->table) && property_exists($order->table, 'name') ? $order->table->name : 'N/A') . "\n";
            $ticket .= "Mesero: " . (isset($order->waiter) && is_object($order->waiter) && property_exists($order->waiter, 'name') ? $order->waiter->name : 'N/A') . "\n";
            $ticket .= "--------------------------------\n";
            
            foreach ($order->items as $item) {
                $ticket .= sprintf("%-20s x%d\n", substr($item->product_name, 0, 20), $item->quantity);
                if ($item->notes) $ticket .= " * " . $item->notes . "\n";
            }
            
            $ticket .= "--------------------------------\n";
            $ticket .= "TOTAL: S/ " . number_format($order->total, 2) . "\n";
            $ticket .= "--------------------------------\n";
            $ticket .= "       GRACIAS POR SU COMPRA    \n\n\n";

            Log::info($ticket);

            /*
            $printer->text($ticket);
            $printer->cut();
            $printer->close();
            */

            return true;
        } catch (\Exception $e) {
            Log::error("Printing error: " . $e->getMessage());
            return false;
        }
    }
}