<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Frontend\Home;
use App\Livewire\Frontend\About;
use App\Livewire\Frontend\Contact;
use App\Livewire\Frontend\Menu;
use App\Livewire\Frontend\BookaTable;
use App\Livewire\Frontend\Cart;
use App\Livewire\Frontend\FromOurMenu;

Route::get('/', function () {
    return view('livewire.pages.front.home');
});

Route::get('/about', About::class);
Route::get('/contact', Contact::class);
Route::get('/menu', Menu::class);
Route::get('/bookaTable', BookaTable::class);
Route::get('/cart', Cart::class);
Route::get('/fromOurMenu', FromOurMenu::class);

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
