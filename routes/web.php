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

// Frontend Routes
Route::get('/', function () {return view('livewire.pages.front.home');});
Route::get('/about', About::class);
Route::get('/contact', Contact::class);
Route::get('/menu', Menu::class);
Route::get('/bookaTable', BookaTable::class);
Route::get('/cart', Cart::class);
Route::get('/fromOurMenu', FromOurMenu::class);

//Backend uses
use App\Livewire\UserController;
use App\Livewire\CouponCodeController;
use App\Livewire\OrderItemController;
use App\Livewire\MenuItemController;
use App\Livewire\PostOfficeController;
use App\Livewire\SiteSettingsController;

Route::view(uri: 'dashboard', view: 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('users', UserController::class)
    ->middleware(['auth'])
    ->name('users');
        
Route::get('site_settings', SiteSettingsController::class)
    ->middleware(['auth'])
    ->name('site_settings');
        
Route::get('post_offices', PostOfficeController::class)
    ->middleware(['auth'])
    ->name('post_offices');
        
Route::get('menu_items', MenuItemController::class)
    ->middleware(['auth'])
    ->name('menu_items');
        
Route::get('order_items', OrderItemController::class)
    ->middleware(['auth'])
    ->name('order_items');
        
Route::get('coupon_codes', CouponCodeController::class)
    ->middleware(['auth'])
    ->name('coupon_codes');

require __DIR__.'/auth.php';