<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('last_order_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('last_order_date', '<=', $request->date_to);
        }

        // Filter by minimum spent
        if ($request->filled('min_spent')) {
            $query->where('total_spent', '>=', $request->min_spent);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'last_order_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $customers = $query->paginate(20);

        // Statistics
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'total_revenue' => Customer::sum('total_spent'),
            'average_spent' => Customer::avg('total_spent'),
        ];

        return view('customers.index', compact('customers', 'stats'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_lastname' => 'nullable|string|max:255',
            'customer_dni' => 'nullable|string|unique:customers,customer_dni',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'delivery_address' => 'nullable|string',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified customer
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        // Get customer orders
        $orders = Order::where(function ($query) use ($customer) {
            $query->where('customer_email', $customer->customer_email)
                ->orWhere('customer_phone', $customer->customer_phone)
                ->orWhere('customer_dni', $customer->customer_dni);
        })
            ->with(['items.product', 'table', 'waiter'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate additional stats
        $stats = [
            'completed_orders' => $orders->where('status', 'delivered')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'pending_orders' => $orders->whereIn('status', ['pending', 'confirmed', 'preparing'])->count(),
            'favorite_products' => $this->getFavoriteProducts($customer),
        ];

        return view('customers.show', compact('customer', 'orders', 'stats'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_lastname' => 'nullable|string|max:255',
            'customer_dni' => 'nullable|string|unique:customers,customer_dni,' . $id,
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'delivery_address' => 'nullable|string',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified customer
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }

    /**
     * Export customers to Excel
     */
    public function export()
    {
        // This will be implemented with Laravel Excel
        return redirect()->back()->with('info', 'Exportación en desarrollo.');
    }

    /**
     * Get customer's favorite products
     */
    private function getFavoriteProducts($customer)
    {
        return DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where(function ($query) use ($customer) {
                $query->where('orders.customer_email', $customer->customer_email)
                    ->orWhere('orders.customer_phone', $customer->customer_phone)
                    ->orWhere('orders.customer_dni', $customer->customer_dni);
            })
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();
    }
}
