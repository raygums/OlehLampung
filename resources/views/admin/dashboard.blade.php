@extends('layouts.admin')
@section('page-title', 'Dashboard')
@section('content')
{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-navy">{{ $stats['total_products'] }}</p>
                <p class="text-xs text-gray-500">Total Produk</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-light rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-navy">{{ $stats['total_orders'] }}</p>
                <p class="text-xs text-gray-500">Total Pesanan</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-navy">{{ $stats['pending_orders'] }}</p>
                <p class="text-xs text-gray-500">Menunggu</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-navy">Rp {{ number_format($stats['total_revenue'],0,',','.') }}</p>
                <p class="text-xs text-gray-500">Pendapatan</p>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="bg-white rounded-xl border border-gray-200">
    <div class="p-5 border-b flex items-center justify-between">
        <h2 class="font-heading font-semibold text-navy">Pesanan Terbaru</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-amber text-sm hover:underline">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">No. Pesanan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Pelanggan</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-navy hover:text-amber">{{ $order->order_number }}</a></td>
                        <td class="px-5 py-3 text-sm">{{ $order->full_name }}</td>
                        <td class="px-5 py-3 text-sm font-semibold">{{ $order->formatted_total }}</td>
                        <td class="px-5 py-3"><span class="badge text-xs {{ match($order->status_color) { 'yellow'=>'bg-yellow-100 text-yellow-700', 'green'=>'bg-green-100 text-green-700', 'blue'=>'bg-blue-100 text-blue-700', 'red'=>'bg-red-100 text-red-700', default=>'bg-gray-100 text-gray-700' } }}">{{ $order->status_label }}</span></td>
                        <td class="px-5 py-3 text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
