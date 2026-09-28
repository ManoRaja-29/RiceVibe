<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/shop', [SiteController::class, 'shop'])->name('shop');
Route::get('/shop/{slug}', [SiteController::class, 'product'])->name('product');
Route::get('/about-us', [SiteController::class, 'about'])->name('about');
Route::get('/media', [SiteController::class, 'media'])->name('media');
Route::get('/gallery', [SiteController::class, 'gallery'])->name('gallery');
Route::get('/certificates', [SiteController::class, 'certificates'])->name('certificates');
Route::get('/brochure', [SiteController::class, 'brochure'])->name('brochure');
Route::get('/brochure/download', [SiteController::class, 'brochureDownload'])->name('brochure.download');
Route::get('/brochure/view', [SiteController::class, 'brochureView'])->name('brochure.view');
Route::get('/faq', [SiteController::class, 'faq'])->name('faq');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::get('/cart', fn () => redirect()->route('contact'))->name('cart');
Route::get('/privacy-policy', [SiteController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [SiteController::class, 'terms'])->name('terms');
Route::get('/shipping-policy', [SiteController::class, 'shipping'])->name('shipping');
Route::get('/returns-policy', [SiteController::class, 'returns'])->name('returns');
Route::get('/robots.txt', [SiteController::class, 'robots']);
Route::get('/sitemap.xml', [SiteController::class, 'sitemap']);

Route::middleware('guest')->group(function () {
    Route::get('/admin', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin', [AuthController::class, 'login'])->name('admin.login.submit');
});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/uploads', [UploadController::class, 'store'])->name('uploads.store');
    Route::delete('/uploads/{upload}', [UploadController::class, 'destroy'])->name('uploads.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
