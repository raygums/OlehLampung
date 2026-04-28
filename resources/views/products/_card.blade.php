<div class="card group">
    <a href="{{ route('products.show', $product) }}">
        <div class="product-image-wrapper">
            <img src="{{ $product->primary_image }}" alt="{{ $product->name }}">
            <div class="overlay"></div>
            @if($product->is_sale && $product->discount_percent > 0)
                <span class="badge badge-sale absolute top-3 left-3">-{{ $product->discount_percent }}%</span>
            @endif
            <button onclick="event.preventDefault(); addToCart({{ $product->id }})" class="absolute bottom-3 right-3 w-9 h-9 bg-amber text-navy rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0 shadow-lg hover:bg-amber-dark">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
            </button>
        </div>
    </a>
    <div class="card-body">
        <p class="text-xs text-amber-dark font-semibold uppercase tracking-wide mb-1">{{ $product->category->name }}</p>
        <a href="{{ route('products.show', $product) }}" class="font-semibold text-navy text-sm hover:text-amber transition-colors line-clamp-2 mb-1.5">{{ $product->name }}</a>
        <div class="flex items-center gap-1 mb-2">
            <div class="stars">
                @for($i=1;$i<=5;$i++)
                    <svg class="w-3 h-3 {{ $i<=floor($product->rating)?'':'empty' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
            <span class="text-xs text-gray-400">({{ $product->review_count }})</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="price">{{ $product->formatted_price }}</span>
            @if($product->original_price)
                <span class="price-original">{{ $product->formatted_original_price }}</span>
            @endif
        </div>
    </div>
</div>
