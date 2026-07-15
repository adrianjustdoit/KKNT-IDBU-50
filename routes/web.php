<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductShowController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{product}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/produk/{product:slug}', [ProductShowController::class, 'show'])->name('product.3d');
Route::get('/kompos-eksplorasi', [ProductShowController::class, 'eksplorasiKompos'])->name('kompos.eksplorasi');
Route::get('/struktur-organisasi', [HomeController::class, 'struktur'])->name('struktur');

// News routes
Route::get('/berita', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{news:slug}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Temporary test route
Route::get('/test-news-create', function () {
    return view('admin.news.create');
});

// Admin routes (protected)
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products CRUD
    Route::resource('products', ProductController::class)->except(['show']);
    Route::delete('product-image/{image}', [ProductController::class, 'deleteImage'])->name('product-image.destroy');

    // Members CRUD (Struktur Organisasi)
    Route::resource('members', App\Http\Controllers\Admin\MemberController::class)->except(['show']);

    // News CRUD
    Route::resource('news', App\Http\Controllers\Admin\NewsController::class);
    Route::post('news/upload-image', [App\Http\Controllers\Admin\NewsController::class, 'uploadContentImage'])->name('news.upload-image');

    // Media
    Route::get('media/video', [MediaController::class, 'video'])->name('media.video');
    Route::put('media/video', [MediaController::class, 'updateVideo'])->name('media.video.update');
    Route::get('media/gallery', [MediaController::class, 'gallery'])->name('media.gallery');
    Route::post('media/gallery', [MediaController::class, 'storeGallery'])->name('media.gallery.store');
    Route::delete('media/gallery/{photo}', [MediaController::class, 'destroyGallery'])->name('media.gallery.destroy');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});
