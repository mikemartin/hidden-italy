<?php

return [
    'service' => 'Turnstile', // options: Recaptcha / Hcaptcha / Turnstile / Altcha
    'sitekey' => env('TURNSTILE_SITEKEY', ''),
    'secret' => env('TURNSTILE_SECRET', ''),
    'collections' => [],
    'forms' => [],
    'user_login' => false,
    'user_registration' => false,
    'disclaimer' => '',
    'invisible' => false,
    'hide_badge' => false,
    'enable_api_routes' => false,
    'custom_should_verify' => null,
];
