<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OlehLampung — Oleh-oleh Autentik Lampung')</title>
    <meta name="description" content="@yield('meta_description', 'Belanja oleh-oleh khas Lampung terlengkap. Keripik pisang, kopi robusta, kain tapis. Kirim ke mana saja!')">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    {{-- Navbar --}}
    <header class="bg-navy sticky top-0 z-50 shadow-lg">
        <div class="container-main">
            {{-- py-4 memberikan ruang agar menu tidak mepet ke atas/bawah --}}
            <nav class="flex items-center justify-between py-4 h-20">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 text-white font-heading font-bold text-2xl" id="nav-logo">
                    <span class="text-amber">Oleh</span>Lampung
                    <span class="text-amber text-sm">✦</span>
                </a>

                {{-- Desktop Nav Links --}}
                <div class="hidden md:flex items-center gap-8" id="nav-links">
                    <a href="{{ route('home') }}" class="text-sm font-medium text-gray-300 hover:text-amber transition-colors {{ request()->routeIs('home') ? 'text-amber' : '' }}">Beranda</a>
                    
                    @php 
                        $navCategories = \App\Models\Category::whereIn('slug', ['kopi', 'makanan'])
                            ->orderBy('sort_order')
                            ->get(); 
                    @endphp

                    @foreach($navCategories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="text-sm font-medium text-gray-300 hover:text-amber transition-colors {{ request()->is('kategori/'.$cat->slug) ? 'text-amber' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('products.search') }}" class="text-gray-300 hover:text-amber transition-colors p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </a>

                    <a href="{{ auth()->check() ? route('cart.index') : route('login') }}" class="relative text-gray-300 hover:text-amber transition-colors p-2" title="{{ auth()->check() ? 'Keranjang' : 'Masuk untuk belanja' }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        @auth
                            <span class="absolute top-0 right-0 bg-amber text-navy text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center cart-count" id="cart-badge" style="display: none;">0</span>
                        @endauth
                    </a>

                    @auth
                        <div class="relative group cursor-pointer ml-2">
                            <div class="flex items-center gap-2 text-gray-300 hover:text-amber transition-colors">
                                <div class="w-8 h-8 rounded-full bg-navy-light flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <span class="hidden md:block text-sm font-medium">{{ explode(' ', Auth::user()->name)[0] }}</span>
                            </div>
                            <div class="absolute right-0 mt-4 w-48 bg-white rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 overflow-hidden border border-gray-100">
                                @if(str_contains(Auth::user()->email, 'admin'))
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm text-navy hover:bg-gray-50 border-b border-gray-100 font-medium">Panel Admin</a>
                                @else
                                    <a href="{{ route('orders.index') }}" class="block px-4 py-3 text-sm text-navy hover:bg-gray-50 border-b border-gray-100 font-medium">Riwayat Pesanan</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2 transition-colors font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:flex text-sm font-medium text-white hover:bg-amber hover:text-navy transition-all border border-amber/50 rounded-lg px-5 py-2 ml-2 items-center">Masuk</a>
                    @endauth

                    {{-- Mobile Toggle --}}
                    <button class="md:hidden text-gray-300 hover:text-amber p-2" id="mobile-menu-btn">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </nav>

            {{-- Mobile Menu --}}
            <div class="md:hidden hidden pb-6 animate-slide-down" id="mobile-menu">
                <div class="flex flex-col gap-3 pt-4 border-t border-navy-light">
                    <a href="{{ route('home') }}" class="text-base font-medium text-gray-300 hover:text-amber py-2 transition-colors">Beranda</a>
                    @foreach($navCategories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="text-base font-medium text-gray-300 hover:text-amber py-2 transition-colors">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    {{-- mt-10 memberikan jarak agar konten di bawah navbar tidak mepet --}}
    <main class="flex-1 mb-20">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="site-footer" id="site-footer">
        {{-- Decorative top border --}}
        <div class="footer-accent-border"></div>

        <div class="container-main footer-main">
            <div class="footer-grid">
                
                {{-- Kolom 1: Brand Info --}}
                <div class="footer-col footer-brand">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <span class="text-amber">Oleh</span>Lampung
                        <span class="footer-logo-dot">✦</span>
                    </a>
                    <p class="footer-description">
                        Pusat oleh-oleh autentik yang menghadirkan cita rasa terbaik dari Tanah Lampung langsung ke tangan Anda.
                    </p>
                    {{-- Social Icons Row --}}
                    <div class="footer-social-row">
                        <a href="https://instagram.com/username_anda" target="_blank" class="footer-social-icon" aria-label="Instagram" title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://wa.me/628123456789" target="_blank" class="footer-social-icon footer-social-wa" aria-label="WhatsApp" title="WhatsApp">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.407 3.481 2.242 2.242 3.48 5.23 3.481 8.411-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.301 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.319 1.592 5.448 0 9.886-4.438 9.888-9.886.002-5.448-4.438-9.889-9.89-9.891-2.638 0-5.117 1.027-6.982 2.891-1.864 1.865-2.89 4.341-2.891 6.984 0 2.099.644 3.513 1.582 5.055l-.972 3.548 3.666-.961zm10.749-7.72c-.273-.136-1.62-.8-1.87-.891-.249-.09-.431-.136-.613.136-.182.273-.706.891-.864 1.072-.158.182-.317.204-.59.068-.273-.136-1.152-.424-2.196-1.355-.812-.724-1.359-1.618-1.519-1.89-.16-.273-.017-.42.119-.556.123-.122.273-.318.41-.477.136-.159.182-.273.272-.454.09-.181.046-.341-.023-.477-.068-.136-.613-1.477-.841-2.022-.222-.533-.466-.459-.64-.468-.166-.008-.356-.01-.546-.01-.19 0-.5.072-.76.359-.26.287-1.002.977-1.002 2.382 0 1.405 1.024 2.766 1.168 2.958.143.191 2.016 3.078 4.882 4.318.682.295 1.214.471 1.63.604.686.217 1.31.186 1.803.113.55-.081 1.62-.663 1.85-1.303.228-.641.228-1.189.159-1.303-.068-.114-.249-.182-.522-.318z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Kolom 2: Kategori --}}
                <div class="footer-col">
                    <h4 class="footer-heading">Kategori Produk</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('products.category', 'kopi') }}">Kopi Lampung</a></li>
                        <li><a href="{{ route('products.category', 'makanan') }}">Makanan Khas</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Kontak --}}
                <div class="footer-col">
                    <h4 class="footer-heading">Hubungi Kami</h4>
                    <ul class="footer-contact">
                        <li>
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Bandar Lampung, Lampung</span>
                        </li>
                        <li>
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>info@olehlampung.id</span>
                        </li>
                        <li>
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Senin – Sabtu, 08.00 – 17.00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div class="footer-bottom">
            <div class="container-main" style="text-align: center;">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} OlehLampung. Autentik dari Tanah Lampung.
                </p>
            </div>
        </div>
    </footer>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Menu Toggle
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if(mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>