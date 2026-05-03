@extends('layouts.app')
@section('title', 'Pembayaran — OlehLampung')
@section('content')
<div class="container-main py-8 max-w-lg mx-auto text-center">
    <h2 class="font-heading text-xl font-bold text-navy mb-4">Menyelesaikan Pembayaran</h2>
    <p class="text-gray-500 mb-6">Pesanan <strong class="text-amber">{{ $order->order_number }}</strong></p>
    <div class="bg-white rounded-xl border border-gray-200 p-8">
        <p class="text-sm text-gray-500 mb-4">Total: <strong class="text-2xl text-navy">{{ $order->formatted_total }}</strong></p>
        <button id="pay-button" class="btn btn-primary btn-lg btn-block">Lanjutkan Pembayaran</button>
        <p class="text-xs text-gray-400 mt-4">Anda akan diarahkan ke halaman pembayaran Midtrans.</p>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ config('services.midtrans.isProduction') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', function(){
    window.snap.pay('{{ $snapToken }}', {
        onSuccess: function(r){ window.location.href = '{{ route("checkout.success", $order) }}'; },
        onPending: function(r){ window.location.href = '{{ route("orders.show", $order) }}'; },
        onError: function(r){ window.location.href = '{{ route("orders.show", $order) }}'; },
        onClose: function(){ window.location.href = '{{ route("orders.show", $order) }}'; }
    });
});
</script>
@endpush
