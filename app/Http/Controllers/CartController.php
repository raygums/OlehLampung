<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
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

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->get('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        // KITA UBAH DI SINI
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang!',
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        // KITA UBAH DI SINI
        if ($request->wantsJson()) {
            $product = Product::find($id);
            $subtotal = 0;
            foreach ($cart as $cid => $item) {
                $p = Product::find($cid);
                if ($p) $subtotal += $p->price * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'itemSubtotal' => $product ? $product->price * $request->quantity : 0,
                'cartSubtotal' => $subtotal,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        // KITA UBAH JUGA DI SINI (jangan lupa tambah $request di parameternya kalau mau pakai $request->wantsJson(), atau pakai fungsi global request())
        if (request()->wantsJson()) {
            $subtotal = 0;
            foreach ($cart as $cid => $item) {
                $p = Product::find($cid);
                if ($p) $subtotal += $p->price * $item['quantity'];
            }

            return response()->json([
                'success' => true,
                'cartSubtotal' => $subtotal,
                'cartCount' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }

    public function count()
    {
        $cart = session()->get('cart', []);
        return response()->json([
            'count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }
}