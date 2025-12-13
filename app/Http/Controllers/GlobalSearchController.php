<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        if (strlen($query) < 2) {
            return response()->json([
                'orders' => [],
                'products' => [],
            ]);
        }

        // Search Orders
        $orders = Order::where('order_number', 'like', "%{$query}%")
            ->orWhere('customer_name', 'like', "%{$query}%")
            ->orWhere('customer_phone', 'like', "%{$query}%")
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'title' => 'Pedido #'.($order->order_number ?? $order->id),
                    'subtitle' => $order->customer_name.' - '.$order->status,
                    'url' => route('orders.show', $order->id),
                    'type' => 'order',
                ];
            });

        // Search Products
        $products = Product::where('name', 'like', "%{$query}%")
            ->where('is_available', true)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->name,
                    'subtitle' => 'S/ '.$product->price,
                    // Assuming there is a route for editing or viewing products, defaulting to menu index for now if specific edit route isn't standard resource
                    'url' => route('menu.index').'?search='.urlencode($product->name),
                    'type' => 'product',
                ];
            });

        return response()->json([
            'orders' => $orders,
            'products' => $products,
        ]);
    }
}
