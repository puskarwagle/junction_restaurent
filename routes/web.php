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

// Backend Routes
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('menuitems', MenuManagement::class)
    ->middleware(['auth'])
    ->name('menuitems');

Route::get('users', AdminManagement::class)
    ->middleware(['auth'])
    ->name('users');




require __DIR__.'/auth.php';
