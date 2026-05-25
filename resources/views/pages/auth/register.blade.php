@php
    // Honour `?redirect=` so flows like the booking modal can land back on
    // the originating page after registration. Only same-origin paths are
    // accepted to avoid open-redirects.
    $redirect = (string) request()->query('redirect', '');

    if ($redirect !== '' && str_starts_with($redirect, '/') && ! str_starts_with($redirect, '//')) {
        session()->put('url.intended', url($redirect));
    }
@endphp

<x-layouts::auth.split :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email', request()->query('email'))"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password — single field with Flux's viewable toggle so
                 the user can verify what they typed without a second
                 confirmation input. -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <button type="submit" class="button button--large w-full" data-test="register-user-button">
                    <span>{{ __('Create account') }}</span>
                </button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth.split>
