@extends('layouts.app')
@section('title', 'Riwayat Pesanan — OlehLampung')
@section('content')
<div class="orders-page">
    {{-- Page Header --}}
    <div class="orders-header">
        <div class="container-main">
            <div class="orders-header-inner">
                <div class="orders-header-info">
                    <p class="category-header-label">Riwayat</p>
                    <h1 class="orders-header-title">Pesanan Saya <span class="category-header-count">— Lacak dan pantau pesanan Anda</span></h1>
                </div>
                {{-- Lookup --}}
                <form action="{{ route('orders.lookup') }}" method="POST" class="orders-lookup-form">
                    @csrf
                    <div class="orders-lookup-input-wrap">
                        <svg class="orders-lookup-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="order_number" placeholder="Cari nomor pesanan (cth: OL-1234)" class="orders-lookup-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary orders-lookup-btn">Lacak</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container-main orders-body">
        <div class="order-filter-chips">
            <a href="{{ route('orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">Semua</a>
            @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Selesai','cancelled'=>'Dibatalkan'] as $k=>$v)
                <a href="{{ route('orders.index', ['status'=>$k]) }}" class="btn btn-sm {{ request('status')==$k ? 'btn-primary' : 'btn-outline' }}">{{ $v }}</a>
            @endforeach
        </div>

        {{-- Order List --}}
        @if($orders->count())
            <div class="orders-list">
                @foreach($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="order-card" style="animation-delay: {{ $loop->index * 0.05 }}s">
                        {{-- Status accent bar --}}
                        <div class="order-card-accent {{ $order->status_color }}"></div>

                        <div class="order-card-body">
                            {{-- Top row: order number + status --}}
                            <div class="order-card-top">
                                <div class="order-card-id">
                                    <span class="order-card-number">{{ $order->order_number }}</span>
                                    <span class="order-card-date">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </div>
                                <span class="order-status-badge {{ $order->status_color }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>

                            {{-- Bottom row: items + total --}}
                            <div class="order-card-bottom">
                                <div class="order-card-meta">
                                    {{-- Product thumbnails --}}
                                    <div class="order-card-thumbs">
                                        @foreach($order->items->take(3) as $item)
                                            @if($item->product && $item->product->primary_image)
                                                <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="order-card-thumb">
                                            @else
                                                <div class="order-card-thumb order-card-thumb-placeholder">
                                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <div class="order-card-thumb order-card-thumb-more">
                                                +{{ $order->items->count() - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="order-card-items-count">{{ $order->items->count() }} item</span>
                                </div>
                                <div class="order-card-price">
                                    <span class="order-card-total">{{ $order->formatted_total }}</span>
                                    <span class="order-card-name">{{ $order->full_name }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Hover arrow --}}
                        <div class="order-card-arrow">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10">{{ $orders->links() }}</div>
        @else
            @php
                $emptyStates = [
                    'pending'    => ['title' => 'Belum ada pesanan menunggu',        'text' => 'Pesanan yang menunggu pembayaran akan muncul di sini.',    'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'confirmed'  => ['title' => 'Belum ada pesanan dikonfirmasi',    'text' => 'Pesanan yang sudah dikonfirmasi akan muncul di sini.',     'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'processing' => ['title' => 'Belum ada pesanan diproses',        'text' => 'Pesanan yang sedang diproses akan muncul di sini.',       'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                    'shipped'    => ['title' => 'Belum ada pesanan dikirim',         'text' => 'Pesanan yang sedang dalam pengiriman akan muncul di sini.','icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                    'delivered'  => ['title' => 'Belum ada pesanan selesai',         'text' => 'Pesanan yang sudah selesai akan muncul di sini.',         'icon' => 'M5 13l4 4L19 7'],
                    'cancelled'  => ['title' => 'Belum ada pesanan dibatalkan',      'text' => 'Tidak ada pesanan yang dibatalkan. Bagus!',               'icon' => 'M6 18L18 6M6 6l12 12'],
                ];
                $currentStatus = request('status');
                $empty = $emptyStates[$currentStatus] ?? null;
            @endphp

            <div class="orders-empty">
                <div class="orders-empty-icon">
                    @if($empty)
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="{{ $empty['icon'] }}"/></svg>
                    @else
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    @endif
                </div>
                <h3 class="orders-empty-title">{{ $empty ? $empty['title'] : 'Belum ada pesanan' }}</h3>
                <p class="orders-empty-text">{{ $empty ? $empty['text'] : 'Pesanan Anda akan muncul di sini. Mulai belanja sekarang!' }}</p>
                @unless($currentStatus)
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary" style="margin-top: 1.5rem;">Mulai Belanja</a>
                @endunless
            </div>
        @endif
    </div>
</div>
@endsection
