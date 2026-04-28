@extends('layouts.app')
@section('title', 'Pembayaran Berhasil — OlehLampung')
@section('content')
<div class="container-main py-10 max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Success Header --}}
        <div class="bg-gradient-to-b from-green-50 to-white text-center py-10 px-6">
            <div class="w-16 h-16 bg-success rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="font-heading text-2xl font-bold text-navy mb-2">Pembayaran Berhasil!</h1>
            <p class="text-gray-500 text-sm">Terima kasih! Pesanan sedang kami siapkan untuk dikirim.</p>
            <p class="text-amber font-semibold mt-2">📦 Pesanan #{{ $order->order_number }}</p>
        </div>

        <div class="px-6 pb-8">
            {{-- Order Details --}}
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="font-heading font-semibold text-navy text-sm uppercase tracking-wide mb-4">Ringkasan Pesanan</h3>
                <table class="w-full text-sm">
                    <tr class="border-b"><td class="py-2.5 text-gray-500">Tanggal Pesanan</td><td class="py-2.5 text-right font-medium">{{ $order->created_at->translatedFormat('l, d F Y · H:i') }}</td></tr>
                    <tr class="border-b"><td class="py-2.5 text-gray-500">Metode Bayar</td><td class="py-2.5 text-right font-medium">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</td></tr>
                    <tr class="border-b"><td class="py-2.5 text-gray-500">Dikirim ke</td><td class="py-2.5 text-right font-medium">{{ $order->full_name }} — {{ $order->city }}</td></tr>
                    <tr class="border-b"><td class="py-2.5 text-gray-500">Kurir</td><td class="py-2.5 text-right font-medium">{{ strtoupper(str_replace('_',' ',$order->shipping_method)) }}</td></tr>
                    <tr class="border-b"><td class="py-2.5 text-gray-500">Status Pembayaran</td><td class="py-2.5 text-right"><span class="text-success font-semibold">✓ Lunas</span></td></tr>
                    <tr><td class="py-2.5 text-gray-500">Total Pembayaran</td><td class="py-2.5 text-right font-bold text-amber text-lg">{{ $order->formatted_total }}</td></tr>
                </table>
            </div>

            {{-- Items --}}
            <div class="space-y-3 mb-6">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 bg-amber rounded-full"></div>
                        <span class="text-sm flex-1">{{ $item->product_name }} × {{ $item->quantity }}</span>
                        <span class="text-sm font-semibold">{{ $item->formatted_subtotal }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Timeline --}}
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h3 class="font-heading font-semibold text-navy text-sm uppercase tracking-wide mb-4">Status Pesanan</h3>
                <div class="timeline">
                    @php
                        $steps = [
                            ['status' => 'confirmed', 'label' => 'Pembayaran Dikonfirmasi', 'desc' => $order->created_at->translatedFormat('d M · H:i').' WIB'],
                            ['status' => 'processing', 'label' => 'Pesanan Diproses', 'desc' => 'Segera'],
                            ['status' => 'shipped', 'label' => 'Dikirimkan ke Kurir', 'desc' => ''],
                            ['status' => 'in_transit', 'label' => 'Dalam Pengiriman', 'desc' => ''],
                            ['status' => 'delivered', 'label' => 'Pesanan Tiba 🎉', 'desc' => ''],
                        ];
                        $activeFound = false;
                    @endphp
                    @foreach($steps as $step)
                        @php
                            $isCompleted = !$activeFound && ($order->status === $step['status'] || array_search($order->status, array_column($steps, 'status')) > array_search($step['status'], array_column($steps, 'status')));
                            $isActive = $order->status === $step['status'];
                            if ($isActive) $activeFound = true;
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}"></div>
                            <div>
                                <p class="text-sm font-semibold {{ $isCompleted || $isActive ? 'text-navy' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                @if($step['desc'])<p class="text-xs text-gray-400">{{ $step['desc'] }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Actions --}}
            <div class="space-y-3">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary btn-lg btn-block">Lacak Pesanan</a>
                <a href="https://wa.me/?text=Saya baru saja memesan di OlehLampung! Pesanan %23{{ $order->order_number }}" target="_blank" class="btn btn-dark btn-block">Bagikan ke WhatsApp</a>
            </div>

            <div class="text-center mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500">Mau belanja lagi? Temukan lebih banyak oleh-oleh Lampung yang buat!</p>
                <a href="{{ route('home') }}" class="text-amber font-semibold text-sm hover:underline mt-1 inline-block">← Kembali ke beranda</a>
            </div>
        </div>
    </div>
</div>
@endsection
