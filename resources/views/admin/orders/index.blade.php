@extends('layouts.admin')
@section('page-title', 'Manajemen Pesanan')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4 flex-wrap">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status')?'btn-primary':'btn-outline' }}">Semua</a>
        @foreach(['pending'=>'Menunggu','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Selesai','cancelled'=>'Batal'] as $k=>$v)
            <a href="{{ route('admin.orders.index',['status'=>$k]) }}" class="btn btn-sm {{ request('status')==$k?'btn-primary':'btn-outline' }}">{{ $v }}</a>
        @endforeach
    </div>
    <form class="flex gap-2" method="GET">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pesanan..." class="form-input text-sm w-48">
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">No. Pesanan</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Pelanggan</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Item</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Total</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Tanggal</th>
                <th class="text-right text-xs font-semibold text-gray-500 uppercase px-5 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-5 py-3"><a href="{{ route('admin.orders.show', $order) }}" class="font-semibold text-navy hover:text-amber">{{ $order->order_number }}</a></td>
                    <td class="px-5 py-3 text-sm">{{ $order->full_name }}<br><span class="text-xs text-gray-400">{{ $order->whatsapp }}</span></td>
                    <td class="px-5 py-3 text-sm">{{ $order->items->count() }} item</td>
                    <td class="px-5 py-3 text-sm font-semibold">{{ $order->formatted_total }}</td>
                    <td class="px-5 py-3"><span class="badge text-xs {{ match($order->status_color) { 'yellow'=>'bg-yellow-100 text-yellow-700', 'green'=>'bg-green-100 text-green-700', 'blue'=>'bg-blue-100 text-blue-700', 'red'=>'bg-red-100 text-red-700', default=>'bg-gray-100 text-gray-700' } }}">{{ $order->status_label }}</span></td>
                    <td class="px-5 py-3 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 text-right">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-amber hover:underline text-sm">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada pesanan</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endsection
