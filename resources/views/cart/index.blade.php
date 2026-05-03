@extends('layouts.app')
@section('title', 'Keranjang Belanja — OlehLampung')
@section('content')
<div class="container-main py-10">
    <h1 class="font-heading text-2xl font-bold text-navy mb-8">Keranjang Belanja</h1>

    @if(count($cartItems) > 0)
        <div class="grid md:grid-cols-3 gap-8">
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="text-left text-xs font-semibold text-gray-500 uppercase px-6 py-4">Produk</th>
                                <th class="text-center text-xs font-semibold text-gray-500 uppercase px-4 py-4">Jumlah</th>
                                <th class="text-right text-xs font-semibold text-gray-500 uppercase px-6 py-4">Subtotal</th>
                                <th class="px-4 py-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b cart-item" data-id="{{ $item['product']->id }}">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $item['product']->primary_image }}" alt="" class="w-16 h-16 rounded-lg object-cover">
                                            <div>
                                                <a href="{{ route('products.show', $item['product']) }}" class="font-semibold text-navy text-sm hover:text-amber">{{ $item['product']->name }}</a>
                                                <p class="text-xs text-gray-400 mt-1.5">{{ $item['product']->formatted_price }} / pcs</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="flex items-center justify-center border border-gray-300 rounded-lg mx-auto w-fit">
                                            <button onclick="updateQty({{ $item['product']->id }}, -1)" class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-navy">−</button>
                                            <input type="number" value="{{ $item['quantity'] }}" min="1" class="w-10 text-center border-x border-gray-300 h-9 text-sm font-semibold qty-input" data-id="{{ $item['product']->id }}">
                                            <button onclick="updateQty({{ $item['product']->id }}, 1)" class="w-9 h-9 flex items-center justify-center text-gray-500 hover:text-navy">+</button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="font-semibold text-navy item-subtotal" data-id="{{ $item['product']->id }}">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-4 py-5">
                                        <button onclick="removeItem({{ $item['product']->id }})" class="text-gray-400 hover:text-danger transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-xl border border-gray-200 p-7 sticky top-20">
                    <h3 class="font-heading font-semibold text-navy mb-5">Ringkasan Belanja</h3>
                    <div class="space-y-4 text-sm border-b border-gray-200 pb-5 mb-5">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal ({{ count($cartItems) }} produk)</span>
                            <span class="font-semibold" id="cart-subtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="text-gray-400 text-xs">Dihitung saat checkout</span>
                        </div>
                    </div>
                    <div class="flex justify-between font-bold text-lg mb-7">
                        <span>Total</span>
                        <span class="text-amber" id="cart-total">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg btn-block">
                        Lanjut ke Checkout
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            <h3 class="font-heading font-semibold text-navy text-xl mb-2">Keranjang Anda kosong</h3>
            <p class="text-gray-500 mb-6">Yuk mulai belanja oleh-oleh Lampung!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Mulai Belanja</a>
        </div>
    @endif
</div>
@endsection
@push('scripts')
<script>
const csrf = '{{ csrf_token() }}';
function fmt(n){return 'Rp '+n.toLocaleString('id-ID');}
function updateQty(id, d){
    const inp=document.querySelector('.qty-input[data-id="'+id+'"]');
    let v=parseInt(inp.value)+d; if(v<1)v=1; inp.value=v;
    fetch('/keranjang/update/'+id,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf,'Accept':'application/json'},body:JSON.stringify({quantity:v})})
    .then(r=>r.json()).then(data=>{if(data.success){document.querySelector('.item-subtotal[data-id="'+id+'"]').textContent=fmt(data.itemSubtotal);document.getElementById('cart-subtotal').textContent=fmt(data.cartSubtotal);document.getElementById('cart-total').textContent=fmt(data.cartSubtotal);document.getElementById('cart-badge').textContent=data.cartCount;}});
}
function removeItem(id){
    fetch('/keranjang/hapus/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
    .then(r=>r.json()).then(data=>{if(data.success){document.querySelector('.cart-item[data-id="'+id+'"]').remove();document.getElementById('cart-subtotal').textContent=fmt(data.cartSubtotal);document.getElementById('cart-total').textContent=fmt(data.cartSubtotal);document.getElementById('cart-badge').textContent=data.cartCount;if(data.cartCount===0)location.reload();}});
}
</script>
@endpush
