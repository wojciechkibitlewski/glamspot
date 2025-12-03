<?php

use App\Http\Controllers\Admin\AdsAdminController;
use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FirmsAdminController;
use App\Http\Controllers\Admin\PaymentsAdminController;
use App\Http\Controllers\Admin\SettingsAdminController;
use App\Http\Controllers\Admin\UsersAdminController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\WelcomeController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;


Route::get('/', action: [WelcomeController::class, 'index'])->name('home');

// Public routers
Route::get('/o-nas', action: [StaticPagesController::class, 'about'])->name('about');
Route::get('/regulamin', action: [StaticPagesController::class, 'terms'])->name('terms');
Route::get('/prywatnosc', action: [StaticPagesController::class, 'privacy'])->name('privacy');
Route::get('/faq', action: [StaticPagesController::class, 'faq'])->name('faq');

Route::get('/uzytkownik/{code}', [UserAccountController::class, 'show'])->name('user-account.show');

Route::get('/firma/{slug}/{code}', [FirmController::class, 'show'])->name('firm.show');

Route::get('/ogloszenia', [AdsController::class, 'index'])->name('ads.index');
Route::get('/ogloszenia/{code}/{slug}', action: [AdsController::class, 'show'])
    ->where('code', '(?!praca$)[A-Za-z0-9]{5}')
    ->name('ads.show');
Route::get('/ogloszenia/{category:slug}', action: [AdsController::class, 'byCategory'])->name('ads.category');
Route::get('/ogloszenia/{category:slug}/{subcategory:slug}', action: [AdsController::class, 'byCategorySubcategory'])->name('ads.category.sub');
Route::get('/ogloszenia/{category:slug}/{subcategorySlug}/{industriesSlug}/{regionSlug}/{formsIds}', [AdsController::class, 'byPathFilters'])->name('ads.filters');
Route::get('/api/cities', [CityController::class, 'search'])->name('cities.search');

Route::get('/blog', action: [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', action: [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}/{code}-{postSlug}', action: [BlogController::class, 'show'])
    ->where('code', '[A-Za-z0-9]{6}')
    ->name('blog.show');

// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/moje-konto', fn () => redirect('/moje-konto/index'))->name('user-account');
    Route::get('/moje-konto/index', [UserAccountController::class, 'index'])->name('user-account.index');
    Route::get('/moje-konto/zmien-haslo', action: [UserAccountController::class, 'password'])->name('user-account.password');
    Route::get('/moje-konto/rachunki', action: [UserAccountController::class, 'billing'])->name('user-account.billing');
    Route::get('/moje-konto/powiadomienia', action: [UserAccountController::class, 'notifications'])->name('user-account.notifications');
    Route::get('/moje-konto/newsletter', action: [UserAccountController::class, 'newsletter'])->name('user-account.newsletter');
    Route::get('/moje-konto/firma', action: [FirmController::class, 'manage'])->name('user-account.firm');

}); 

// Admin routes
Route::middleware(['auth', 'verified', 'role:Administrator'])->group(function () {
    Route::get('/administracja',  action: [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/administracja/uzytkownicy',  action: [UsersAdminController::class, 'index'])->name('admin.users');
    Route::get('/administracja/ogloszenia',  action: [AdsAdminController::class, 'index'])->name('admin.ads');
    Route::get('/administracja/blog',  action: [BlogAdminController::class, 'index'])->name('admin.blog');
    Route::get('/administracja/platnosci',  action: [PaymentsAdminController::class, 'index'])->name('admin.payments');
    Route::get('/administracja/firmy',  action: [FirmsAdminController::class, 'index'])->name('admin.firms');
    Route::get('/administracja/ustawienia',  action: [SettingsAdminController::class, 'index'])->name('admin.settings');


});



// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Route::get('settings/profile', Profile::class)->name('profile.edit');
//     Route::get('settings/password', Password::class)->name('user-password.edit');
//     Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

//     Route::get('settings/two-factor', TwoFactor::class)
//         ->middleware(
//             when(
//                 Features::canManageTwoFactorAuthentication()
//                     && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
//                 ['password.confirm'],
//                 [],
//             ),
//         )
//         ->name('two-factor.show');
// });

require __DIR__.'/auth.php';