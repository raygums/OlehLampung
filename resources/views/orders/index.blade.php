@extends('layouts.app')
@section('title', 'Riwayat Pesanan — OlehLampung')
@section('content')
<div class="container-main py-10">
    <h1 class="font-heading text-2xl font-bold text-navy mb-1">Riwayat Pesanan</h1>
    <p class="text-gray-500 text-sm mb-8">Lacak dan pantau semua pesanan Anda.</p>

    {{-- Lookup --}}
    <div class="bg-amber-50 rounded-xl p-6 mb-10">
        <p class="text-sm font-semibold text-navy mb-3">Lacak Pesanan Anda</p>
        <form action="{{ route('orders.lookup') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="order_number" placeholder="Masukkan nomor pesanan (cth: OL-1234)" class="form-input flex-1" required>
            <button type="submit" class="btn btn-primary px-6">Lacak</button>
        </form>
    </div>

    {{-- Filter --}}
    <div class="flex items-center gap-2 mb-8 flex-wrap">
        <a href="{{ route('orders.index') }}" class="btn btn-sm {{ !request('status')?'btn-primary':'btn-outline' }}">Semua</a>
        @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Selesai','cancelled'=>'Dibatalkan'] as $k=>$v)
            <a href="{{ route('orders.index', ['status'=>$k]) }}" class="btn btn-sm {{ request('status')==$k?'btn-primary':'btn-outline' }}">{{ $v }}</a>
        @endforeach
    </div>

    @if($orders->count())
        <div class="space-y-4">
            @foreach($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block bg-white rounded-xl border border-gray-200 p-6 hover:border-amber hover:shadow-md transition-all">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-heading font-bold text-navy">{{ $order->order_number }}</span>
                                <span class="badge {{ match($order->status_color) { 'yellow'=>'bg-yellow-100 text-yellow-700', 'green'=>'bg-green-100 text-green-700', 'blue'=>'bg-blue-100 text-blue-700', 'red'=>'bg-red-100 text-red-700', default=>'bg-gray-100 text-gray-700' } }}">{{ $order->status_label }}</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $order->created_at->translatedFormat('d M Y, H:i') }} &middot; {{ $order->items->count() }} item</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-amber text-lg">{{ $order->formatted_total }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $order->full_name }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $orders->links() }}</div>
    @else
        <div class="text-center py-16">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="font-heading text-navy font-semibold text-lg mb-2">Belum ada pesanan</h3>
            <p class="text-gray-500 text-sm">Masukkan nomor pesanan di atas untuk melacak.</p>
        </div>
    @endif
</div>
@endsection
