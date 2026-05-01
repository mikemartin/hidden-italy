<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Hidden Italy') : config('app.name', 'Hidden Italy') }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preload" href="{{ Vite::asset('resources/fonts/MonaSansVF-wght-opsz-ital.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ Vite::asset('resources/fonts/RomaNeue-SemiBold.woff2') }}" as="font" type="font/woff2" crossorigin>

@vite(['resources/css/site.css', 'resources/js/site.js'])
@fluxAppearance
