<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EduwisataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationTrackingController;
use App\Http\Middleware\EnsureCheckoutAuthentication;
use Illuminate\Support\Facades\Route;

// 1. Katalog & Halaman Informasi (Public / Guest)
Route::get('/', [CatalogController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/tentang-agrinova', [HomeController::class, 'index'])->name('about');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::view('/terms', 'terms')->name('terms');

// 2. Autentikasi Google OAuth
Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');

// 3. Keranjang Belanja (Guest bisa tambah/ubah keranjang)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

// 4. Checkout 3 Tahap (Wajib Login Google via EnsureCheckoutAuthentication)
Route::middleware(EnsureCheckoutAuthentication::class)->group(function () {
    // Step 1: Detail Pemesanan
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Step 2: Pembayaran QRIS Statis (15 Menit)
    Route::get('/checkout/payment/{orderNumber}', [CheckoutController::class, 'payment'])->name('checkout.payment');

    // Step 3: Konfirmasi & Kirim Bukti via WhatsApp
    Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');
});

// 5. Akun, Riwayat Pesanan & Review
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/orders/{orderNumber}/tracking', [OrderTrackingController::class, 'show'])->name('orders.tracking');
    Route::post('/products/{product:slug}/reviews', [ProductReviewController::class, 'store'])->name('products.reviews.store');
});

// 6. Layanan Eduwisata & Pelatihan Budidaya
Route::get('/eduwisata', [EduwisataController::class, 'index'])->name('eduwisata.index');
Route::get('/eduwisata/paket/{slug}', [EduwisataController::class, 'show'])->name('eduwisata.show');
Route::get('/eduwisata/reservasi/{slug}', [EduwisataController::class, 'create'])->name('eduwisata.create');
Route::post('/eduwisata/reservasi', [EduwisataController::class, 'reserve'])->name('eduwisata.reserve');

Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');
Route::get('/pelatihan/{slug}', [PelatihanController::class, 'show'])->name('pelatihan.show');
Route::get('/pelatihan/{slug}/daftar', [PelatihanController::class, 'create'])->name('pelatihan.create');
Route::post('/pelatihan/{slug}/daftar', [PelatihanController::class, 'register'])->name('pelatihan.register');

Route::get('/lacak-pendaftaran', [RegistrationTrackingController::class, 'index'])->name('registrations.track');
Route::post('/lacak-pendaftaran', [RegistrationTrackingController::class, 'lookup'])->name('registrations.lookup');
Route::get('/pendaftaran/{type}/{reference}', [RegistrationTrackingController::class, 'show'])->name('registrations.show');

// 7. Route Admin Terpisah
require __DIR__ . '/admin.php';