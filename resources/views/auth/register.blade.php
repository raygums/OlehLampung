<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — OlehLampung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('auth._styles')
</head>
<body>
    <div class="auth-wrap">
        {{-- Background --}}
        <div class="auth-bg">
            <img src="{{ asset('images/auth-bg.png') }}" alt="">
        </div>
        <div class="auth-bg-overlay"></div>

        {{-- Floating particles --}}
        <div class="auth-particle"></div>
        <div class="auth-particle"></div>
        <div class="auth-particle"></div>

        {{-- Main content --}}
        <div class="auth-container">
            <div class="auth-logo">
                <a href="{{ route('home') }}">
                    <span class="l-amber">Oleh</span>Lampung
                    <span class="l-dot">✦</span>
                </a>
                <p>Buat akun baru untuk mulai belanja</p>
            </div>

            <div class="auth-card">
                <h2 class="auth-heading">Daftar Akun ✨</h2>
                <p class="auth-sub">Isi data di bawah untuk membuat akun baru</p>

                @if($errors->any())
                    <div class="auth-error">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    {{-- Nama --}}
                    <div class="auth-field">
                        <label class="auth-label" for="name">Nama Lengkap</label>
                        <div class="auth-input-wrap">
                            <input type="text" name="name" id="name" class="auth-input"
                                   value="{{ old('name') }}" required autofocus placeholder="Masukkan nama Anda">
                            <div class="auth-input-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="auth-field">
                        <label class="auth-label" for="email">Email</label>
                        <div class="auth-input-wrap">
                            <input type="email" name="email" id="email" class="auth-input"
                                   value="{{ old('email') }}" required placeholder="nama@email.com">
                            <div class="auth-input-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div class="auth-field">
                        <label class="auth-label" for="password">
                            Password <span class="auth-label-hint">(Min. 8 karakter)</span>
                        </label>
                        <div class="auth-input-wrap">
                            <input type="password" name="password" id="password" class="auth-input"
                                   required placeholder="••••••••">
                            <div class="auth-input-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            @include('auth._toggle_password', ['inputId' => 'password'])
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="auth-field">
                        <label class="auth-label" for="password_confirmation">Konfirmasi Password</label>
                        <div class="auth-input-wrap">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="auth-input" required placeholder="••••••••">
                            <div class="auth-input-icon">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            @include('auth._toggle_password', ['inputId' => 'password_confirmation'])
                        </div>
                    </div>

                    <button type="submit" class="auth-submit">Daftar Sekarang</button>
                </form>

                <div class="auth-divider"><span>atau</span></div>

                <div class="auth-footer">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            </div>

            <div class="auth-back">
                <a href="{{ route('home') }}">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke toko
                </a>
            </div>
        </div>
    </div>

    {{-- Bottom branding --}}
    <div class="auth-branding">
        <span>Produk Autentik</span>
        <div class="auth-branding-dot"></div>
        <span>Kirim se-Indonesia</span>
        <div class="auth-branding-dot"></div>
        <span>Kualitas Terjamin</span>
    </div>

    @include('auth._scripts')
</body>
</html>