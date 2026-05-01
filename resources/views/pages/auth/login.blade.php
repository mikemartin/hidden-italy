<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Layout('layouts.auth.split')]
#[Title('Log in')]
class extends Component
{
    public string $email = '';

    public ?User $foundUser = null;

    public bool $confirming = false;

    public function mount(): void
    {
        $this->email = (string) request()->query('email', '');
    }

    public function continue(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->redirect(route('register', ['email' => $this->email]), navigate: true);

            return;
        }

        $this->foundUser = $user;
        $this->confirming = true;
    }

    public function changeEmail(): void
    {
        $this->confirming = false;
        $this->foundUser = null;
    }

    public function firstName(): string
    {
        if (! $this->foundUser) {
            return '';
        }

        return str($this->foundUser->name)->explode(' ')->first() ?? '';
    }
}; ?>

<div class="flex flex-col gap-6">
    @if (! $confirming)
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email to continue')" />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="continue" class="flex flex-col gap-6">
            <flux:input
                wire:model="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <div class="flex items-center justify-end">
                <button type="submit" class="button button--accent button--large w-full" data-test="login-continue-button">
                    <span>{{ __('Continue') }}</span>
                </button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        @endif
    @else
        <x-auth-header
            :title="__('Welcome back :name', ['name' => $this->firstName()])"
            :description="__('Enter your password to log in')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <div class="flex items-center justify-between gap-3 p-3 border rounded-lg border-zinc-200">
            <div class="min-w-0">
                <div class="text-sm font-medium truncate text-zinc-900">
                    {{ $foundUser->name }}
                </div>
                <div class="text-xs truncate text-zinc-600">
                    {{ $foundUser->email }}
                </div>
            </div>
            <flux:link
                class="text-sm cursor-pointer shrink-0"
                wire:click.prevent="changeEmail"
            >
                {{ __('Change') }}
            </flux:link>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}" />

            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autofocus
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <flux:checkbox name="remember" :label="__('Remember me')" />

            <div class="flex items-center justify-end">
                <button type="submit" class="button button--accent button--large w-full" data-test="login-button">
                    <span>{{ __('Log in') }}</span>
                </button>
            </div>
        </form>
    @endif
</div>
