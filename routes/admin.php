<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEduwisataPackageController;
use App\Http\Controllers\Admin\AdminEduwisataReservationController;
use App\Http\Controllers\Admin\AdminGalleryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPreOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminTrainingController;
use App\Http\Controllers\Admin\AdminTrainingRegistrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Master Produk & Layanan (Dropdown Produk)
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::resource('categories', AdminCategoryController::class)->except(['show', 'create', 'edit']);
        Route::resource('eduwisata-packages', AdminEduwisataPackageController::class)->except(['show']);
        Route::resource('trainings', AdminTrainingController::class)->except(['show']);

        // Banner Katalog & Countdown Pre-order
        Route::get('/preorders/banner-settings', fn () => redirect()->route('admin.preorders.index'));
        Route::resource('preorders', AdminPreOrderController::class)->except(['show']);
        Route::post('/preorders/banner-settings', [AdminPreOrderController::class, 'updateBanner'])->name('preorders.banner');

        // Transaksi & Reservasi Masuk
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

        Route::get('/eduwisata', [AdminEduwisataReservationController::class, 'index'])->name('eduwisata.index');
        Route::patch('/eduwisata/{reservation}', [AdminEduwisataReservationController::class, 'update'])->name('eduwisata.update');

        Route::get('/training-registrations', [AdminTrainingRegistrationController::class, 'index'])->name('training-registrations.index');
        Route::patch('/training-registrations/{registration}', [AdminTrainingRegistrationController::class, 'update'])->name('training-registrations.update');

        // Galeri, Ulasan & Pengaturan
        Route::resource('gallery', AdminGalleryController::class)->except(['show']);
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});