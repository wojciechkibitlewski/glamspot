<?php

use App\Http\Controllers\Admin\AdsAdminController;
use App\Http\Controllers\Admin\BlogAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FirmsAdminController;
use App\Http\Controllers\Admin\PaymentsAdminController;
use App\Http\Controllers\Admin\SettingsAdminController;
use App\Http\Controllers\Admin\UsersAdminController;
use App\Http\Controllers\Admin\RolesAdminController;
use App\Http\Controllers\Admin\PostCategoryAdminController;
use App\Http\Controllers\AdsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StaticPagesController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserAdsController;
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

// Newsletter
Route::get('/newsletter/wypisz', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

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

// Payments
Route::post('/platnosci/zamowienia', [PaymentController::class, 'store'])->name('orders.store');


// User routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/moje-konto', fn () => redirect('/moje-konto/index'))->name('user-account');
    Route::get('/moje-konto/index', [UserAccountController::class, 'index'])->name('user-account.index');
    Route::get('/moje-konto/zmien-haslo', action: [UserAccountController::class, 'password'])->name('user-account.password');
    Route::get('/moje-konto/rachunki', action: [UserAccountController::class, 'billing'])->name('user-account.billing');
    Route::get('/moje-konto/powiadomienia', action: [UserAccountController::class, 'notifications'])->name('user-account.notifications');
    Route::get('/moje-konto/newsletter', action: [UserAccountController::class, 'newsletter'])->name('user-account.newsletter');
    Route::get('/moje-konto/firma', action: [FirmController::class, 'manage'])->name('user-account.firm');

    Route::get('/moje-ogloszenia', action: [UserAdsController::class, 'index'])->name('user-ads.index');
    Route::get('/moje-ogloszenia/{code}/{slug}', action: [UserAdsController::class, 'show'])->name('user-ads.show');
    Route::get('/moje-ogloszenia/{code}/{slug}/edytuj', action: [UserAdsController::class, 'edit'])->name('user-ads.edit');
    Route::put('/moje-ogloszenia/{code}/{slug}', action: [UserAdsController::class, 'update'])->name('user-ads.update');
    Route::delete('/moje-ogloszenia/{code}/{slug}', action: [UserAdsController::class, 'destroy'])->name('user-ads.destroy');
    Route::get('/moje-ogloszenia/{code}/{slug}/platnosc', action: [UserAdsController::class, 'checkout'])->name('user-ads.checkout');
    Route::get('/moje-ogloszenia/nowe-ogloszenie', action: [UserAdsController::class, 'create'])->name('user-ads.create');
    Route::post('/moje-ogloszenia/nowe-ogloszenie', action: [UserAdsController::class, 'store'])->name('user-ads.store');


}); 

// Admin routes
Route::middleware(['auth', 'verified', 'role:Administrator'])->group(function () {
    Route::get('/administracja',  action: [DashboardController::class, 'index'])->name('admin.dashboard');
    
      Route::get('/administracja/uzytkownicy',  action: [UsersAdminController::class, 'index'])->name('admin.users');
      Route::prefix('/administracja/role')->name('admin.roles.')->group(function () {
          Route::get('/', [RolesAdminController::class, 'index'])->name('index');
          Route::get('/create', [RolesAdminController::class, 'create'])->name('create');
          Route::post('/', [RolesAdminController::class, 'store'])->name('store');
          Route::get('/{role}/edit', [RolesAdminController::class, 'edit'])->name('edit');
          Route::put('/{role}', [RolesAdminController::class, 'update'])->name('update');
          Route::delete('/{role}', [RolesAdminController::class, 'destroy'])->name('destroy');
      });
    
      Route::get('/administracja/ogloszenia',  action: [AdsAdminController::class, 'index'])->name('admin.ads');
      Route::prefix('/administracja/ogloszenia/kategorie')->name('admin.ads.categories.')->group(function () {
          Route::get('/', [AdsAdminController::class, 'category'])->name('index');
          Route::get('/create', [AdsAdminController::class, 'createCategory'])->name('create');
          Route::post('/', [AdsAdminController::class, 'storeCategory'])->name('store');
          Route::get('/{category}/edit', [AdsAdminController::class, 'editCategory'])->name('edit');
          Route::put('/{category}', [AdsAdminController::class, 'updateCategory'])->name('update');
          Route::delete('/{category}', [AdsAdminController::class, 'destroyCategory'])->name('destroy');
      });

      Route::prefix('/administracja/ogloszenia/podkategorie')->name('admin.ads.subcategories.')->group(function () {
          Route::get('/', [AdsAdminController::class, 'subcategories'])->name('index');
          Route::get('/create', [AdsAdminController::class, 'createSubcategory'])->name('create');
          Route::post('/', [AdsAdminController::class, 'storeSubcategory'])->name('store');
          Route::get('/{subcategory}/edit', [AdsAdminController::class, 'editSubcategory'])->name('edit');
          Route::put('/{subcategory}', [AdsAdminController::class, 'updateSubcategory'])->name('update');
          Route::delete('/{subcategory}', [AdsAdminController::class, 'destroySubcategory'])->name('destroy');
      });
    
      Route::get('/administracja/blog',  action: [BlogAdminController::class, 'index'])->name('admin.blog');
      Route::get('/administracja/blog/dodaj',  action: [BlogAdminController::class, 'create'])->name('admin.blog.create');
      Route::post('/administracja/blog',  action: [BlogAdminController::class, 'store'])->name('admin.blog.store');
      Route::get('/administracja/blog/{id}/edytuj',  action: [BlogAdminController::class, 'edit'])->name('admin.blog.edit');
      Route::put('/administracja/blog/{id}',  action: [BlogAdminController::class, 'update'])->name('admin.blog.update');

      Route::prefix('/administracja/blog/kategorie')->name('admin.blog.categories.')->group(function () {
          Route::get('/', [PostCategoryAdminController::class, 'category'])->name('index');
          Route::get('/create', [PostCategoryAdminController::class, 'create'])->name('create');
          Route::post('/', [PostCategoryAdminController::class, 'store'])->name('store');
          Route::get('/{postCategory}/edit', [PostCategoryAdminController::class, 'edit'])->name('edit');
          Route::put('/{postCategory}', [PostCategoryAdminController::class, 'update'])->name('update');
          Route::delete('/{postCategory}', [PostCategoryAdminController::class, 'destroy'])->name('destroy');
      });
    
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
