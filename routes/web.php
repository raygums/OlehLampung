<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Route;

// ==========================================
// RUTE PUBLIK (Bebas diakses tanpa login)
// ==========================================

Route::get('/', function() {
    return view('home', [
        'categories' => \App\Models\Category::whereIn('slug', ['kopi', 'makanan'])->orderBy('sort_order')->get(),
        'featuredProducts' => \App\Models\Product::active()->featured()
            ->whereHas('category', fn($q) => $q->whereIn('slug', ['kopi', 'makanan']))
            ->with('category')->take(4)->get(),
    ]);
})->name('home');

// Products
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/cari', [ProductController::class, 'search'])->name('products.search');
Route::get('/kategori/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');

// Midtrans callback (WAJIB PUBLIK agar Midtrans bisa ngirim status pembayaran)
Route::post('/midtrans/callback', [CheckoutController::class, 'midtransCallback'])->name('midtrans.callback');


// ==========================================
// ZONA WAJIB LOGIN (User & Admin)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Cart (Keranjang)
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/keranjang/count', [CartController::class, 'count'])->name('cart.count');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/berhasil/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Orders (Riwayat Pesanan)
    Route::get('/pesanan', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/pesanan/lacak', [OrderController::class, 'lookup'])->name('orders.lookup');
    Route::get('/pesanan/{order}', [OrderController::class, 'show'])->name('orders.show');

});


// ==========================================
// RUTE ADMIN KHUSUS
// ==========================================
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
});


// ==========================================
// RUTE AUTENTIKASI (Login & Logout)
// ==========================================
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $request->session()->regenerate();
        
        // Cek apakah yang login ini admin
        if (str_contains($credentials['email'], 'admin')) {
            // HAPUS fungsi intended(), paksa langsung ke halaman admin
            return redirect()->route('admin.dashboard'); 
        }
        
        // Kalau user biasa, biarkan pakai intended() biar dia balik ke tujuannya (misal: keranjang)
        return redirect()->intended(route('home'));
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
})->middleware('guest');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout')->middleware('auth');

// Rute untuk menampilkan form pendaftaran
Route::get('/register', function () {
    return view('auth.register');
})->name('register')->middleware('guest');

// Rute untuk memproses data pendaftaran
Route::post('/register', function (\Illuminate\Http\Request $request) {
    // 1. Validasi isian form
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // 2. Simpan user baru ke database
    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
    ]);

    // 3. Langsung login-kan otomatis setelah berhasil daftar
    \Illuminate\Support\Facades\Auth::login($user);
    $request->session()->regenerate();

    // 4. Lempar ke Beranda
    return redirect()->route('home');
})->middleware('guest');