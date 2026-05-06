@extends('layouts.app')
@section('title', $product->name.' — OlehLampung')
@section('content')
<div class="container-main py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home') }}" class="hover:text-amber">Beranda</a>
        <span>/</span>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-amber">{{ $product->category->name }}</a>
        <span>/</span>
        <span class="text-navy">{{ $product->name }}</span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10">
        {{-- Image Gallery --}}
        <div>
            <div class="rounded-xl overflow-hidden border border-gray-200 mb-4">
                <img id="main-image" src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover">
            </div>
            @if($product->images && count($product->images) > 1)
                <div class="flex gap-3">
                    @foreach($product->images as $i => $img)
                        <button onclick="document.getElementById('main-image').src='{{ $img }}'" class="w-20 h-20 rounded-lg overflow-hidden border-2 {{ $i===0?'border-amber':'border-gray-200' }} hover:border-amber transition-colors">
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            <span class="badge badge-category mb-3">{{ $product->category->name }}</span>
            <h1 class="font-heading text-2xl md:text-3xl font-bold text-navy mb-3">{{ $product->name }}</h1>

            <div class="flex items-center gap-3 mb-4">
                <div class="stars">
                    @for($i=1;$i<=5;$i++)
                        <svg class="w-4 h-4 {{ $i<=floor($product->rating)?'':'empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-500">{{ $product->rating }} ({{ $product->review_count }} ulasan)</span>
            </div>

            <div class="flex items-end gap-3 mb-6">
                <span class="text-3xl font-bold text-danger">{{ $product->formatted_price }}</span>
                @if($product->original_price)
                    <span class="text-lg text-gray-400 line-through">{{ $product->formatted_original_price }}</span>
                    <span class="badge badge-sale">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            <p class="text-gray-600 mb-6 leading-relaxed">{{ $product->short_description }}</p>

            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-700">Stok:</span>
                @if($product->stock > 0)
                    <span class="text-sm text-success font-semibold">Tersedia ({{ $product->stock }})</span>
                @else
                    <span class="text-sm text-danger font-semibold">Habis</span>
                @endif
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500">Berat: {{ $product->weight }}g</span>
            </div>

            {{-- Add to Cart --}}
            <form id="add-to-cart-form" class="flex items-center gap-4 mb-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center border border-gray-300 rounded-lg">
                    <button type="button" onclick="changeQty(-1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-navy">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                    <input type="number" name="quantity" id="qty-input" value="1" min="1" max="{{ $product->stock }}" class="w-14 text-center border-x border-gray-300 h-10 text-sm font-semibold">
                    <button type="button" onclick="changeQty(1)" class="w-10 h-10 flex items-center justify-center text-gray-500 hover:text-navy">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/></svg>
                    </button>
                </div>
                <button type="submit" class="btn btn-primary btn-lg flex-1" {{ $product->stock < 1 ? 'disabled' : '' }}>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Tambah ke Keranjang
                </button>
            </form>

            {{-- Tabs --}}
            <div class="border-t border-gray-200 pt-6">
                <div class="flex gap-6 border-b border-gray-200 mb-4">
                    <button class="tab-btn active pb-3 text-sm font-semibold text-amber border-b-2 border-amber" data-tab="desc">Deskripsi</button>
                    <button class="tab-btn pb-3 text-sm font-semibold text-gray-400 border-b-2 border-transparent hover:text-gray-600" data-tab="info">Informasi</button>
                </div>
                <div id="tab-desc" class="tab-content">
                    <div class="text-sm text-gray-600 leading-relaxed">{!! nl2br(e($product->description)) !!}</div>
                </div>
                <div id="tab-info" class="tab-content hidden">
                    <table class="w-full text-sm">
                        <tr class="border-b"><td class="py-2 text-gray-500 w-1/3">Kategori</td><td class="py-2 font-medium">{{ $product->category->name }}</td></tr>
                        <tr class="border-b"><td class="py-2 text-gray-500">Berat</td><td class="py-2 font-medium">{{ $product->weight }} gram</td></tr>
                        <tr class="border-b"><td class="py-2 text-gray-500">Stok</td><td class="py-2 font-medium">{{ $product->stock }} unit</td></tr>
                        <tr><td class="py-2 text-gray-500">Rating</td><td class="py-2 font-medium">{{ $product->rating }} / 5.0</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->count())
        <section class="mt-16">
            <h2 class="section-title mb-8">Produk Terkait</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($relatedProducts as $rp)
                    @include('products._card', ['product' => $rp])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
@push('scripts')
<script>
function changeQty(d){const i=document.getElementById('qty-input');let v=parseInt(i.value)+d;if(v<1)v=1;if(v>parseInt(i.max))v=parseInt(i.max);i.value=v;}
document.getElementById('add-to-cart-form').addEventListener('submit',function(e){
    e.preventDefault();
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest
    const fd=new FormData(this);
    fetch('{{ route("cart.add") }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json','Content-Type':'application/json'},body:JSON.stringify({product_id:fd.get('product_id'),quantity:parseInt(fd.get('quantity'))})})
    .then(r=>{
        if (r.redirected || r.status === 401) { window.location.href = '{{ route("login") }}'; return; }
        return r.json();
    })
    .then(data=>{if(data && data.success){document.getElementById('cart-badge').textContent=data.cartCount;document.getElementById('cart-badge').style.display='flex';showToast(data.message,'success');}});
});
document.querySelectorAll('.tab-btn').forEach(btn=>{btn.addEventListener('click',function(){document.querySelectorAll('.tab-btn').forEach(b=>{b.classList.remove('active','text-amber','border-amber');b.classList.add('text-gray-400','border-transparent');});this.classList.add('active','text-amber','border-amber');this.classList.remove('text-gray-400','border-transparent');document.querySelectorAll('.tab-content').forEach(c=>c.classList.add('hidden'));document.getElementById('tab-'+this.dataset.tab).classList.remove('hidden');});});
function addToCart(id){
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest
    fetch('{{ route("cart.add") }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:JSON.stringify({product_id:id,quantity:1})})
    .then(r=>{
        if (r.redirected || r.status === 401) { window.location.href = '{{ route("login") }}'; return; }
        return r.json();
    })
    .then(d=>{if(d && d.success){document.getElementById('cart-badge').textContent=d.cartCount;document.getElementById('cart-badge').style.display='flex';showToast(d.message,'success');}});
}
function showToast(m,t){const e=document.querySelector('.toast');if(e)e.remove();const d=document.createElement('div');d.className='toast toast-'+t;d.textContent=m;document.body.appendChild(d);requestAnimationFrame(()=>d.classList.add('show'));setTimeout(()=>{d.classList.remove('show');setTimeout(()=>d.remove(),400)},3000);}
</script>
@endpush
