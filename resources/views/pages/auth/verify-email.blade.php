<x-layouts::auth :title="__('Email verification')">
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !text-green-600">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="button button--accent button--large w-full">
                    <span>{{ __('Resend verification email') }}</span>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="button-inline" data-test="logout-button">
                    <span>{{ __('Log out') }}</span>
                </button>
            </form>
        </div>
    </div>
</x-layouts::auth>
