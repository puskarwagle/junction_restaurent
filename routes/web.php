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
Route::get('/', function () {
    return view('livewire.pages.front.home');
});
Route::get('/about', About::class);
Route::get('/contact', Contact::class);
Route::get('/menu', Menu::class);
Route::get('/bookaTable', BookaTable::class);
Route::get('/cart', Cart::class);
Route::get('/fromOurMenu', FromOurMenu::class);





//Backend uses
use App\Livewire\Backend\MenuManagement;
use App\Livewire\Backend\AdminManagement;
use App\Livewire\TestCrud\TestCrudController;
use App\Livewire\SiteSettings\SiteSettingsController;
use App\Livewire\PostOfficeController;

Route::view(uri: 'dashboard', view: 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('menuitems', MenuManagement::class)
    ->middleware(['auth'])
    ->name('menuitems');

// Route::get('users', AdminManagement::class)
//     ->middleware(['auth'])
//     ->name('users');

Route::get('users', AdminManagement::class)
    ->middleware(['auth'])
    ->name('users');
        
Route::get('test_cruds', TestCrudController::class)
    ->middleware(['auth'])
    ->name('test_cruds');
        
Route::get('site_settings', SiteSettingsController::class)
    ->middleware(['auth'])
    ->name('site_settings');

Route::get('post_office', PostOfficeController::class)
    ->middleware(['auth'])
    ->name('post_office');

require __DIR__.'/auth.php';