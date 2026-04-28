<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — OlehLampung</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-navy min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <div class="text-center mb-8">
            <h1 class="font-heading text-3xl font-bold text-white"><span class="text-amber">Oleh</span>Lampung <span class="text-amber text-sm">✦</span></h1>
            <p class="text-gray-400 mt-2">Masuk ke panel admin</p>
        </div>

        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg p-3 mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="admin@olehlampung.com">
                </div>
                <div class="mb-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">Masuk</button>
            </form>
        </div>

        <p class="text-center text-gray-500 text-sm mt-6">
            <a href="{{ route('home') }}" class="text-amber hover:underline">← Kembali ke toko</a>
        </p>
    </div>
</body>
</html>
