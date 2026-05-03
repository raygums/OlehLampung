<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity'],
                ];
                $subtotal += $product->price * $item['quantity'];
            }
        }

        return view('checkout.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'whatsapp' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        // Calculate totals
        $subtotal = 0;
        $items = [];
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $itemSubtotal = $product->price * $item['quantity'];
                $items[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemSubtotal,
                ];
                $subtotal += $itemSubtotal;
            }
        }

        // Shipping cost
        $shippingCosts = [
            'jne_reguler' => 18000,
            'jne_yes' => 35000,
            'jnt_express' => 15000,
            'kurir_lokal' => 1,
        ];
        $shippingCost = $shippingCosts[$request->shipping_method] ?? 18000;
        $total = $subtotal + $shippingCost;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'whatsapp' => $request->whatsapp,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'notes' => $request->notes,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Decrease stock
                $item['product']->decrement('stock', $item['quantity']);
            }

            // Get Midtrans Snap Token
            $snapToken = $this->getMidtransSnapToken($order);
            if ($snapToken) {
                $order->update(['snap_token' => $snapToken]);
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');

            if ($snapToken) {
                return view('checkout.payment', compact('order', 'snapToken'));
            }

            return redirect()->route('checkout.success', $order)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function success(Order $order)
    {
        $order->load('items.product');
        return view('checkout.success', compact('order'));
    }

    private function getMidtransSnapToken(Order $order): ?string
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

        if (!\Midtrans\Config::$serverKey || \Midtrans\Config::$serverKey === 'your-server-key-here') {
            return null;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'email' => $order->email,
                'phone' => $order->whatsapp,
            ],
            'item_details' => [],
        ];

        foreach ($order->items as $item) {
            $params['item_details'][] = [
                'id' => $item->product_id,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'name' => substr($item->product_name, 0, 50),
            ];
        }

        // Add shipping as item
        $params['item_details'][] = [
            'id' => 'SHIPPING',
            'price' => $order->shipping_cost,
            'quantity' => 1,
            'name' => 'Ongkos Kirim',
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Exception $e) {
            // Log error but don't block order
            \Log::error('Midtrans token error: ' . $e->getMessage());
        }

        return null;
    }

    public function midtransCallback(Request $request)
    {
        \Log::info('Midtrans Webhook Received:', $request->all());

        $serverKey = config('services.midtrans.serverKey');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            \Log::warning('Midtrans Webhook Invalid Signature', ['expected' => $hashed, 'received' => $request->signature_key]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::where('order_number', $request->order_id)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update(['payment_status' => 'pending']);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $order->update([
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
