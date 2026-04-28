<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%");
            });
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function lookup(Request $request)
    {
        $request->validate(['order_number' => 'required|string']);

        $order = Order::where('order_number', $request->order_number)->first();

        if (!$order) {
            return back()->with('error', 'Pesanan tidak ditemukan.');
        }

        return redirect()->route('orders.show', $order);
    }
}
