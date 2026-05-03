<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-background text-text font-sans flex flex-col antialiased">
        @php
            $loggedIn = auth()->check();
            $currentUser = auth()->user() ? \Statamic\Facades\User::fromUser(auth()->user()) : null;
        @endphp

        <s:partial src="layout/announcement_banner" :logged_in="$loggedIn" :current_user="$currentUser" />
        <s:partial src="layout/header" :logged_in="$loggedIn" :current_user="$currentUser" />

        <main class="flex-1 fluid-grid py-12 lg:py-16">
            <div class="span-xl">
                {{ $slot }}
            </div>
        </main>

        <s:partial src="layout/footer" :logged_in="$loggedIn" :current_user="$currentUser" />

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
