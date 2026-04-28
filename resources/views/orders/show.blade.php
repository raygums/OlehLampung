@extends('layouts.app')
@section('title', 'Pesanan '.$order->order_number.' — OlehLampung')
@section('content')
<div class="container-main py-8 max-w-2xl mx-auto">
    <a href="{{ route('orders.index') }}" class="text-amber text-sm hover:underline mb-4 inline-block">← Kembali ke daftar pesanan</a>

    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="p-6 border-b bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-heading text-xl font-bold text-navy">Pesanan {{ $order->order_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $order->created_at->translatedFormat('l, d F Y · H:i') }}</p>
                </div>
                <span class="badge text-sm px-3 py-1 {{ match($order->status_color) { 'yellow'=>'bg-yellow-100 text-yellow-700', 'green'=>'bg-green-100 text-green-700', 'blue'=>'bg-blue-100 text-blue-700', 'red'=>'bg-red-100 text-red-700', default=>'bg-gray-100 text-gray-700' } }}">{{ $order->status_label }}</span>
            </div>
        </div>

        <div class="p-6">
            {{-- Details --}}
            <table class="w-full text-sm mb-6">
                <tr class="border-b"><td class="py-2.5 text-gray-500 w-1/3">Penerima</td><td class="py-2.5 font-medium">{{ $order->full_name }}</td></tr>
                <tr class="border-b"><td class="py-2.5 text-gray-500">WhatsApp</td><td class="py-2.5 font-medium">{{ $order->whatsapp }}</td></tr>
                <tr class="border-b"><td class="py-2.5 text-gray-500">Alamat</td><td class="py-2.5 font-medium">{{ $order->address }}, {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}</td></tr>
                <tr class="border-b"><td class="py-2.5 text-gray-500">Kurir</td><td class="py-2.5 font-medium">{{ strtoupper(str_replace('_',' ',$order->shipping_method)) }}</td></tr>
                <tr><td class="py-2.5 text-gray-500">Pembayaran</td><td class="py-2.5 font-medium">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</td></tr>
            </table>

            {{-- Items --}}
            <h3 class="font-semibold text-navy text-sm mb-3">Item Pesanan</h3>
            <div class="space-y-2 mb-6">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        @if($item->product)
                            <img src="{{ $item->product->primary_image }}" class="w-12 h-12 rounded object-cover">
                        @endif
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $item->product_name }} × {{ $item->quantity }}</p>
                        </div>
                        <span class="text-sm font-semibold">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="border-t pt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>{{ $order->formatted_subtotal }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Ongkos Kirim</span><span>Rp {{ number_format($order->shipping_cost,0,',','.') }}</span></div>
                <div class="flex justify-between font-bold text-lg border-t pt-3 mt-2"><span>Total</span><span class="text-amber">{{ $order->formatted_total }}</span></div>
            </div>
        </div>
    </div>
</div>
@endsection
