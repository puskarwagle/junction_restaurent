<?php
use Illuminate\Support\Facades\Route;

// Frontend uses
use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\About;
use App\Livewire\Frontend\Contact;
use App\Livewire\Frontend\Menu;
use App\Livewire\Frontend\BookaTable;
use App\Livewire\Frontend\Cart;
use App\Livewire\Frontend\FromOurMenu;
use App\Livewire\Frontend\Checkout;

// Frontend Routes
Route::get('/', function () {return view('livewire.pages.front.home');});
Route::get('/about', About::class);
Route::get('/contact', Contact::class);
Route::get('/menu', Menu::class);
Route::get('/bookaTable', BookaTable::class);
Route::get('/cart', Cart::class);
Route::get('/fromOurMenu', FromOurMenu::class);
Route::get('/checkout', Checkout::Class);

//Backend uses
use App\Livewire\Backend\DashboardController;
use App\Livewire\Backend\UserController;
use App\Livewire\Backend\MenuItemController;
use App\Livewire\Backend\TableBookingsController;
use App\Livewire\Backend\SiteSettingsController;
use App\Livewire\Backend\CouponCodeController;

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'livewire.pages.back.profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

Route::get('users', UserController::class)
    ->middleware(['auth', 'verified'])
    ->name('users');
        
Route::get('menu_items', MenuItemController::class)
    ->middleware(['auth', 'verified'])
    ->name('menu_items');
        
Route::get('site_settings', SiteSettingsController::class)
    ->middleware(['auth'])
    ->name('site_settings');
        
Route::get('table_bookings', TableBookingsController::class)
    ->middleware(['auth'])
    ->name('table_bookings');
        
Route::get('coupon_codes', CouponCodeController::class)
    ->middleware(['auth'])
    ->name('coupon_codes');


require __DIR__.'/auth.php';