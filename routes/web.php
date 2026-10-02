<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/shop', [SiteController::class, 'shop'])->name('shop.index');
Route::get('/shop/{slug}', [SiteController::class, 'product'])->name('shop.product');
Route::redirect('/cart', '/contact');
Route::get('/contact', [SiteController::class, 'contact'])->name('contact');
Route::post('/contact', [SiteController::class, 'submitEnquiry'])->middleware('throttle:6,1')->name('contact.submit');
Route::get('/about-us', fn (SiteController $site) => $site->page('about'))->name('about');
Route::get('/gallery', fn (SiteController $site) => $site->page('gallery'))->name('gallery');
Route::get('/media', fn (SiteController $site) => $site->page('media'))->name('media');
Route::get('/certificates', fn (SiteController $site) => $site->page('certificates'))->name('certificates');
Route::get('/faq', fn (SiteController $site) => $site->page('faq'))->name('faq');
Route::get('/brochure', fn (SiteController $site) => $site->page('brochure'))->name('brochure');
Route::get('/privacy-policy', fn (SiteController $site) => $site->page('privacy'))->name('privacy');
Route::get('/terms-and-conditions', fn (SiteController $site) => $site->page('terms'))->name('terms');
Route::get('/shipping-policy', fn (SiteController $site) => $site->page('shipping'))->name('shipping');
Route::get('/returns-policy', fn (SiteController $site) => $site->page('returns'))->name('returns');

Route::get('/brochure/download', function () {
    $custom = Storage::disk('public')->path('brochures/ricevibe-brochure.pdf');
    $fallback = public_path('static/assets/ricevibe/jp_brochure.pdf');
    return response()->download(is_file($custom) ? $custom : $fallback, 'Ricevibe-Brochure.pdf');
})->name('brochure.download');

Route::get('/brochure/view', function () {
    $custom = Storage::disk('public')->path('brochures/ricevibe-brochure.pdf');
    $fallback = public_path('static/assets/ricevibe/jp_brochure.pdf');
    return response()->file(is_file($custom) ? $custom : $fallback, ['Content-Type' => 'application/pdf']);
})->name('brochure.view');

Route::get('/robots.txt', fn () => response("User-agent: *\nAllow: /\nSitemap: ".url('/sitemap.xml')."\n", 200, ['Content-Type' => 'text/plain']));
Route::get('/sitemap.xml', fn (SiteController $site) => $site->sitemap())->name('sitemap');

Route::get('/admin', fn () => redirect()->route('admin.login'));
Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('admin.login.submit');
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/brochure', [AdminController::class, 'brochure'])->name('brochure');
    Route::put('/brochure', [AdminController::class, 'updateBrochure'])->name('brochure.update');
    Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    Route::post('/banners', [AdminController::class, 'storeBanner'])->name('banners.store');
    Route::put('/banners/{banner}', [AdminController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banners/{banner}', [AdminController::class, 'destroyBanner'])->name('banners.destroy');
    Route::put('/content/{section}', [AdminController::class, 'updateContent'])->name('content.update');
});
