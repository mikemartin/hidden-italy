<?php

use Illuminate\Support\Facades\Route;

Route::statamic('wishlist', 'wishlist.index', [
    'title' => 'Wishlist',
]);

Route::statamic('booking', 'booking.index', [
    'title' => 'Booking enquiry',
    'layout' => 'booking.layout',
]);

Route::statamic('booking/thank-you', 'booking.thank-you', [
    'title' => 'Thank you for your enquiry',
]);

Route::statamic('flux-components', 'flux-components', [
    'title' => 'Flux Components',
]);

Route::statamic('form-inputs', 'form-inputs', [
    'title' => 'Form Inputs',
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
});

/*
|--------------------------------------------------------------------------
| Legacy hiddenitaly.com.au redirects
|--------------------------------------------------------------------------
| 301s from the old ASP.NET site to the current pages. The old site
| distinguished most pages by query string (e.g. /guided-tours/page.aspx?p=5),
| so those are matched by path here and switched on the query parameter.
*/

// Simple one-to-one redirects (path only).
$legacyRedirects = [
    'guided-tours' => '/tours/guided',
    'self-guided-walks' => '/tours/self-guided',
    'contact-us' => '/contact',
    'contact-us/subscribe.aspx' => '/contact',
    'booking/book-now.aspx' => '/booking',
    'whats-on/news' => '/inspiration',
    'whats-on/blog' => '/inspiration',
    'whats-on/blog/default.aspx' => '/inspiration',
    'about-us/default.aspx' => '/about',
    'why-choose-us/default.aspx' => '/about#why-tour-with-us',
    'our-people/default.aspx' => '/about#our-people',
    'testimonials/default.aspx' => '/about',
    'ready-to-go' => '/tips',
    'trails-to-freedom' => 'https://hiddenitaly.myshopify.com/products/trails-to-freedom?variant=52010565534016',
    'faqs' => '/contact',
    'australasia' => '/',
    'australasia/page.aspx' => '/',
];

foreach ($legacyRedirects as $from => $to) {
    Route::redirect($from, $to, 301);
}

// Query-string redirects: same path, destination depends on the parameter.
$legacyQueryRedirects = [
    'guided-tours/page.aspx' => ['p', '/tours/guided', [
        '5' => '/tours/guided/sicily-in-the-spring',
        '22' => '/tours/guided/sardinia-in-the-spring',
        '7' => '/tours/guided/puglia-in-the-spring',
        '2077' => '/tours/guided/abruzzo-and-molise',
        '48' => '/tours/guided/turin-and-val-daosta',
        '45' => '/tours/guided/campania-and-the-amalfi-coast',
        '35' => '/tours/guided/trails-to-freedom',
        '25' => '/tours/guided/trieste-and-friuli',
        '18' => '/tours/guided/verona-and-the-dolomites',
    ]],
    'self-guided-walks/page.aspx' => ['p', '/tours/self-guided', [
        '53' => '/tours/self-guided/alba',
        '1089' => '/tours/self-guided/valley-del-lys',
        '1' => '/tours/self-guided/cinque-terre',
        '62' => '/tours/self-guided/dolomites',
        '1080' => '/tours/self-guided/italian-riviera-1',
        '1081' => '/tours/self-guided/italian-riviera-1',
        '1084' => '/tours/self-guided/italian-riviera-2',
        '38' => '/tours/self-guided/lake-como',
        '49' => '/tours/self-guided/portofino',
        '65' => '/tours/self-guided/umbria1',
        '75' => '/tours/self-guided/umbria1',
        '66' => '/tours/self-guided/umbria2',
        '69' => '/tours/self-guided/lazio',
        '26' => '/tours/self-guided/la-tuscia',
        '8' => '/tours/self-guided/tuscany1',
        '22' => '/tours/self-guided/tuscany2',
        '18' => '/tours/self-guided/tuscany3',
        '30' => '/tours/self-guided/umbria-classic',
        '34' => '/tours/self-guided/amalfi-coast',
        '80' => '/tours/self-guided/puglia',
        '46' => '/tours/self-guided/sicily1',
        '58' => '/tours/self-guided/sicily2',
    ]],
    'whats-on/blog/details.aspx' => ['a', '/inspiration', [
        '16' => '/inspiration/the-moorish-delicacies-of-palermo',
        '17' => '/inspiration/pilates-lake-a-hike-in-the-haunted-sibillini-mountains',
        '18' => '/inspiration/a-culinary-treasure-in-the-marble-mountains-of-tuscany',
        '11' => '/inspiration/crossing-the-alps-following-the-trails-to-freedom',
    ]],
    'whats-on/page.aspx' => ['p', '/inspiration', [
        '25' => 'https://www.facebook.com/hidden.italy.walking.tours',
        '32' => 'https://www.instagram.com/hiddenitalywalkingtours',
    ]],
    'footer/default.aspx' => ['p', '/', [
        '6' => '/privacy',
        '34' => '/privacy-app',
        '14' => '/terms',
    ]],
];

foreach ($legacyQueryRedirects as $from => [$param, $fallback, $map]) {
    Route::get($from, function () use ($param, $fallback, $map) {
        return redirect($map[request()->query($param)] ?? $fallback, 301);
    });
}
