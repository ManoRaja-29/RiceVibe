<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/* ---------- Public site ---------- */
Route::get('/', HomeController::class)->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:enquiry')->name('contact.store');

foreach (['fertility' => 'fertility', 'gynaecology' => 'gynaecology', 'pregnancy-maternity-care' => 'maternity'] as $uri => $section) {
    Route::get("/$uri", [ServiceController::class, 'index'])->defaults('section', $section)->name($section);
    Route::get("/$uri/{slug}", [ServiceController::class, 'show'])->defaults('section', $section)->name("$section.show");
}

Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
Route::get('/blogs/{slug}', [BlogController::class, 'show'])->name('blogs.show');
Route::get('/activities', [ConferenceController::class, 'index'])->name('activities.index');
Route::get('/activities/{slug}', [ConferenceController::class, 'show'])->name('activities.show');
Route::get('/consult-qa', [QuestionController::class, 'index'])->name('qa.index');
Route::get('/consult-qa/ask', [QuestionController::class, 'ask'])->name('qa.ask');
Route::post('/consult-qa/ask', [QuestionController::class, 'store'])->middleware('throttle:enquiry')->name('qa.store');
Route::get('/consult-qa/{id}', [QuestionController::class, 'show'])->whereNumber('id')->name('qa.show');
Route::get('/stories', [StoryController::class, 'index'])->name('stories');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/* ---------- Admin ---------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', Admin\DashboardController::class)->name('dashboard');
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('account', [Admin\AuthController::class, 'account'])->name('account');
        Route::put('account', [Admin\AuthController::class, 'updateAccount'])->name('account.update');

        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::resource('categories', Admin\CategoryController::class)->except('show');
        Route::resource('conferences', Admin\ConferenceController::class)->except('show');
        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('faqs', Admin\FaqController::class)->except('show');
        Route::resource('pregnancy', Admin\PregnancyStageController::class)->except('show')->parameters(['pregnancy' => 'id']);
        Route::resource('profile-items', Admin\ProfileItemController::class)->except('show')->parameters(['profile-items' => 'id']);
        Route::resource('gallery', Admin\GalleryItemController::class)->except('show')->parameters(['gallery' => 'id']);

        Route::resource('stories', Admin\StoryController::class)->except('show');
        Route::resource('questions', Admin\QuestionController::class)->except('show');
        Route::resource('enquiries', Admin\EnquiryController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('media', Admin\MediaController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['media' => 'medium']);

        Route::get('settings/{group?}', [Admin\SettingController::class, 'edit'])->name('settings');
        Route::put('settings/{group}', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
