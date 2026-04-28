@extends('layouts.admin')
@section('page-title', 'Detail Pesanan '.$order->order_number)
@section('content')
<a href="{{ route('admin.orders.index') }}" class="text-amber text-sm hover:underline mb-4 inline-block">← Kembali</a>

<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        {{-- Order Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-heading font-semibold text-navy">Informasi Pesanan</h3>
                <span class="badge text-sm px-3 py-1 {{ match($order->status_color) { 'yellow'=>'bg-yellow-100 text-yellow-700', 'green'=>'bg-green-100 text-green-700', 'blue'=>'bg-blue-100 text-blue-700', 'red'=>'bg-red-100 text-red-700', default=>'bg-gray-100 text-gray-700' } }}">{{ $order->status_label }}</span>
            </div>
            <table class="w-full text-sm">
                <tr class="border-b"><td class="py-2 text-gray-500 w-1/3">No. Pesanan</td><td class="py-2 font-semibold">{{ $order->order_number }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-500">Tanggal</td><td class="py-2">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
                <tr class="border-b"><td class="py-2 text-gray-500">Pembayaran</td><td class="py-2">{{ ucwords(str_replace('_',' ',$order->payment_method)) }} — <span class="{{ $order->payment_status==='paid'?'text-success':'text-amber' }} font-semibold">{{ $order->payment_status==='paid'?'Lunas':'Menunggu' }}</span></td></tr>
                <tr class="border-b"><td class="py-2 text-gray-500">Kurir</td><td class="py-2">{{ strtoupper(str_replace('_',' ',$order->shipping_method)) }}</td></tr>
                <tr><td class="py-2 text-gray-500">Catatan</td><td class="py-2">{{ $order->notes ?: '-' }}</td></tr>
            </table>
        </div>

        {{-- Items --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Item Pesanan</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        @if($item->product)<img src="{{ $item->product->primary_image }}" class="w-12 h-12 rounded object-cover">@endif
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-400">{{ $item->formatted_price }} × {{ $item->quantity }}</p>
                        </div>
                        <span class="text-sm font-semibold">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t mt-4 pt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>{{ $order->formatted_subtotal }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Ongkir</span><span>Rp {{ number_format($order->shipping_cost,0,',','.') }}</span></div>
                <div class="flex justify-between font-bold text-lg border-t pt-2"><span>Total</span><span class="text-amber">{{ $order->formatted_total }}</span></div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        {{-- Customer --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Pelanggan</h3>
            <p class="font-semibold text-navy">{{ $order->full_name }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $order->email }}</p>
            <p class="text-sm text-gray-500">WA: {{ $order->whatsapp }}</p>
            <div class="mt-4 pt-4 border-t text-sm text-gray-600">
                <p>{{ $order->address }}</p>
                <p>{{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}</p>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Update Status</h3>
            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" class="form-select text-sm mb-3">
                    @foreach(['pending'=>'Menunggu Pembayaran','confirmed'=>'Pembayaran Dikonfirmasi','processing'=>'Pesanan Diproses','shipped'=>'Dikirimkan ke Kurir','in_transit'=>'Dalam Pengiriman','delivered'=>'Pesanan Tiba','cancelled'=>'Dibatalkan'] as $k=>$v)
                        <option value="{{ $k }}" {{ $order->status===$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary btn-sm btn-block">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
