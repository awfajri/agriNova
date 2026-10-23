<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransNotificationController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPreOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureAdminAuthentication;
use App\Http\Middleware\EnsureCheckoutAuthentication;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('auth.logout');
Route::post('/products/{product:slug}/reviews', [ProductReviewController::class, 'store'])
    ->middleware('auth')
    ->name('products.reviews.store');
Route::get('/orders/{orderNumber}/tracking', [OrderTrackingController::class, 'show'])
    ->middleware('auth')
    ->name('orders.tracking');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/items', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/items', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware(EnsureCheckoutAuthentication::class)->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
Route::get('/order/success/{orderNumber}', [CheckoutController::class, 'success'])->name('order.success');
Route::post('/payments/midtrans/notification', [MidtransNotificationController::class, 'store'])
    ->name('payments.midtrans.notification');

Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('admin.login.store');
Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('admin.logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(EnsureAdminAuthentication::class)
    ->group(function (): void {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
        Route::resource('preorders', AdminPreOrderController::class)->except(['show']);
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    });
