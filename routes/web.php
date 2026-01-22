<?php

use Illuminate\Support\Facades\Route;

Route::statamic('wishlist', 'wishlist.index', [
   'title' => 'Wishlist'
]);

Route::statamic('account', 'account.index', [
   'title' => 'Account',
   'layout' => 'account.layout'
]);

Route::statamic('login', 'account.login', [
   'title' => 'Login',
   'layout' => 'account.layout'
]);

Route::statamic('register', 'account.register', [
   'title' => 'Create account',
   'layout' => 'account.layout'
]);
