<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OlehLampung — Oleh-oleh Autentik Lampung')</title>
    <meta name="description" content="@yield('meta_description', 'Belanja oleh-oleh khas Lampung terlengkap. Keripik pisang, kopi robusta, kain tapis, dan kerajinan Lampung. Kirim ke mana saja!')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">
    {{-- Navbar --}}
    <header class="bg-navy sticky top-0 z-50 shadow-lg">
        <div class="container-main">
            <nav class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-heading font-bold text-xl" id="nav-logo">
                    <span class="text-amber">Oleh</span>Lampung
                    <span class="text-amber text-sm">✦</span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-6" id="nav-links">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-300 hover:text-amber transition-colors {{ request()->routeIs('home') ? 'text-amber' : '' }}">Beranda</a>
                    @php $navCategories = \App\Models\Category::orderBy('sort_order')->get(); @endphp
                    @foreach($navCategories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="text-sm font-medium text-gray-300 hover:text-amber transition-colors {{ request()->is('kategori/'.$cat->slug) ? 'text-amber' : '' }}">{{ $cat->name }}</a>
                    @endforeach
                    <a href="#" class="text-sm font-medium text-gray-300 hover:text-amber transition-colors">Tentang Kami</a>
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <a href="{{ route('products.search') }}" class="text-gray-300 hover:text-amber transition-colors p-2" id="nav-search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative text-gray-300 hover:text-amber transition-colors p-2" id="nav-cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <span class="absolute -top-0.5 -right-0.5 bg-amber text-navy text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center cart-count" id="cart-badge" style="display: none;">0</span>
                    </a>

                    {{-- Mobile Menu Toggle --}}
                    <button class="md:hidden text-gray-300 hover:text-amber p-2" id="mobile-menu-btn">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </nav>

            {{-- Mobile Menu --}}
            <div class="md:hidden hidden pb-4 animate-slide-down" id="mobile-menu">
                <div class="flex flex-col gap-2 pt-2 border-t border-navy-light">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-300 hover:text-amber py-2 transition-colors">Beranda</a>
                    @foreach($navCategories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="text-sm font-medium text-gray-300 hover:text-amber py-2 transition-colors">{{ $cat->name }}</a>
                    @endforeach
                    <a href="#" class="text-sm font-medium text-gray-300 hover:text-amber py-2 transition-colors">Tentang Kami</a>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="toast toast-success show" id="flash-toast">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="toast toast-error show" id="flash-toast">{{ session('error') }}</div>
    @endif

    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-navy text-gray-400 mt-auto">
        <div class="container-main py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                {{-- About --}}
                <div>
                    <h4 class="text-white font-heading font-semibold mb-4">Tentang Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber transition-colors">Kisah Kami</a></li>
                        <li><a href="#" class="hover:text-amber transition-colors">Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-amber transition-colors">Keberlangsutan</a></li>
                    </ul>
                </div>
                {{-- Products --}}
                <div>
                    <h4 class="text-white font-heading font-semibold mb-4">Produk</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('products.category', 'kopi') }}" class="hover:text-amber transition-colors">Kopi</a></li>
                        <li><a href="{{ route('products.category', 'makanan') }}" class="hover:text-amber transition-colors">Makanan</a></li>
                        <li><a href="{{ route('products.category', 'kerajinan') }}" class="hover:text-amber transition-colors">Kerajinan</a></li>
                    </ul>
                </div>
                {{-- Support --}}
                <div>
                    <h4 class="text-white font-heading font-semibold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber transition-colors">Hubungi Kami</a></li>
                        <li><a href="#" class="hover:text-amber transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-amber transition-colors">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                {{-- Social --}}
                <div>
                    <h4 class="text-white font-heading font-semibold mb-4">Ikuti Kami</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-amber transition-colors">Instagram</a></li>
                        <li><a href="#" class="hover:text-amber transition-colors">Facebook</a></li>
                        <li><a href="#" class="hover:text-amber transition-colors">Twitter</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="border-t border-navy-light">
            <div class="container-main py-4 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-sm">&copy; {{ date('Y') }} OlehLampung. Semua hak dilindungi.</p>
                <div class="flex items-center gap-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-navy-light flex items-center justify-center hover:bg-amber hover:text-navy transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-navy-light flex items-center justify-center hover:bg-amber hover:text-navy transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Toast Auto-dismiss --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('flash-toast');
            if (toast) {
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 400);
                }, 3000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
