<?php

use Illuminate\Support\Facades\Route;

Route::statamic('wishlist', 'wishlist.index', [
    'title' => 'Wishlist',
]);

// Booking enquiry requires a `?tour=` query param so the form has
// something to enquire about. Visitors who land at /booking with no
// tour are sent to the catalogue (with `?from=booking` so the page
// can show a "Pick a tour to start your enquiry" banner).
Route::statamic('booking', 'booking.index', [
    'title' => 'Booking enquiry',
    'layout' => 'booking.layout',
])->middleware([
    function (\Illuminate\Http\Request $request, \Closure $next) {
        return $request->filled('tour') ? $next($request) : redirect('/tours?from=booking');
    },
]);

Route::statamic('booking/thank-you', 'booking.thank-you', [
    'title' => 'Thank you for your enquiry',
]);

Route::statamic('flux-components', 'flux-components', [
    'title' => 'Flux Components',
]);

// Override Fortify's GET /login with a Volt-mounted route so the email-first
// 2-step flow can use Livewire state. Fortify still owns POST /login (login.store).
Route::livewire('login', 'pages::auth.login')
    ->middleware('guest')
    ->name('login');

Route::middleware('auth')->group(function () {
    // The account landing surfaces the user's activity (enquiries),
    // not their settings — the booking-enquiry log is the headline
    // reason to hold an account.
    Route::redirect('account', 'account/enquiries')->name('account');

    Route::livewire('account/enquiries', 'pages::settings.enquiries')->name('account.enquiries');

    Route::livewire('account/profile', 'pages::settings.profile')->name('account.profile');
    Route::livewire('account/password', 'pages::settings.password')->name('account.password');
    Route::livewire('account/emergency-contact', 'pages::settings.emergency-contact')->name('account.emergency-contact');
    Route::livewire('account/postal-address', 'pages::settings.postal-address')->name('account.postal-address');
});
