<x-layouts::auth :title="__('Verify your email')">
    <div class="mt-4 flex flex-col gap-6">
        <flux:heading size="lg" class="text-center">
            {{ __('One last step') }}
        </flux:heading>

        <flux:text class="text-center">
            {{ __("We've sent a verification link to :email. Click it to confirm your email address — that's it. You'll be able to view your enquiries straight after.", ['email' => auth()->user()?->email]) }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !text-green-600">
                {{ __("We've sent another verification link. Please check your inbox.") }}
            </flux:text>
        @endif

        <flux:text size="sm" class="text-center text-text/70">
            {{ __("Don't see the email? Check your spam folder, or resend it below.") }}
        </flux:text>

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
