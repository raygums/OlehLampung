@extends('layouts.app')
@section('title', 'Pesanan '.$order->order_number.' — OlehLampung')
@section('content')
<div class="order-detail-page">
    <div class="container-main">
        {{-- Back link --}}
        <a href="{{ route('orders.index') }}" class="order-detail-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke daftar pesanan
        </a>

        <div class="order-detail-card">
            {{-- Header --}}
            <div class="order-detail-header">
                <div class="order-detail-header-left">
                    <div class="order-detail-number-row">
                        <h1 class="order-detail-number">{{ $order->order_number }}</h1>
                        <span class="order-status-badge lg {{ $order->status_color }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                    <p class="order-detail-date">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $order->created_at->translatedFormat('l, d F Y · H:i') }}
                    </p>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="order-detail-info">
                <div class="order-info-item">
                    <div class="order-info-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <span class="order-info-label">Penerima</span>
                        <span class="order-info-value">{{ $order->full_name }}</span>
                    </div>
                </div>
                <div class="order-info-item">
                    <div class="order-info-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <span class="order-info-label">WhatsApp</span>
                        <span class="order-info-value">{{ $order->whatsapp }}</span>
                    </div>
                </div>
                <div class="order-info-item full-width">
                    <div class="order-info-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <span class="order-info-label">Alamat Pengiriman</span>
                        <span class="order-info-value">{{ $order->address }}, {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}</span>
                    </div>
                </div>
                <div class="order-info-item">
                    <div class="order-info-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    </div>
                    <div>
                        <span class="order-info-label">Kurir</span>
                        <span class="order-info-value">{{ strtoupper(str_replace('_',' ',$order->shipping_method)) }}</span>
                    </div>
                </div>
                <div class="order-info-item">
                    <div class="order-info-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div>
                        <span class="order-info-label">Pembayaran</span>
                        <span class="order-info-value">{{ ucwords(str_replace('_',' ',$order->payment_method)) }}</span>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="order-detail-section">
                <h3 class="order-detail-section-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Item Pesanan
                    <span class="order-detail-section-count">{{ $order->items->count() }} item</span>
                </h3>
                <div class="order-items-list">
                    @foreach($order->items as $item)
                        <div class="order-item-row">
                            <div class="order-item-image">
                                @if($item->product)
                                    <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}">
                                @else
                                    <div class="order-item-image-placeholder">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-info">
                                <p class="order-item-name">{{ $item->product_name }}</p>
                                <p class="order-item-qty">Qty: {{ $item->quantity }}</p>
                            </div>
                            <span class="order-item-price">{{ $item->formatted_subtotal }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Totals --}}
            <div class="order-detail-totals">
                <div class="order-total-row">
                    <span>Subtotal</span>
                    <span>{{ $order->formatted_subtotal }}</span>
                </div>
                <div class="order-total-row">
                    <span>Ongkos Kirim</span>
                    <span>Rp {{ number_format($order->shipping_cost,0,',','.') }}</span>
                </div>
                <div class="order-total-row total">
                    <span>Total Pembayaran</span>
                    <span>{{ $order->formatted_total }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
