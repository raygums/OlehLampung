<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        $recentOrders = Order::with('items')->latest()->take(10)->get();
        $categories = Category::withCount('products')->orderBy('sort_order')->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'categories'));
    }
}
