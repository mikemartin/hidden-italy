<?php

use Illuminate\Support\Facades\Route;

Route::statamic('wishlist', 'wishlist.index', [
    'title' => 'Wishlist',
]);

// Override Fortify's GET /login with a Volt-mounted route so the email-first
// 2-step flow can use Livewire state. Fortify still owns POST /login (login.store).
Route::livewire('login', 'pages::auth.login')
    ->middleware('guest')
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::redirect('account', 'account/profile')->name('account');

    Route::livewire('account/profile', 'pages::settings.profile')->name('account.profile');
    Route::livewire('account/password', 'pages::settings.password')->name('account.password');
    Route::livewire('account/emergency-contact', 'pages::settings.emergency-contact')->name('account.emergency-contact');
    Route::livewire('account/postal-address', 'pages::settings.postal-address')->name('account.postal-address');
});
