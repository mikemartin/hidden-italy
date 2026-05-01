<?php

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Profile settings')] class extends Component {
    use ProfileValidationRules;

    public string $name = '';

    public string $email = '';

    public string $phone_home = '';

    public string $phone_italy = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone_home = (string) ($user->phone_home ?? '');
        $this->phone_italy = (string) ($user->phone_italy ?? '');
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $rules = array_merge($this->profileRules($user->id), [
            'phone_home' => ['nullable', 'string', 'max:50'],
            'phone_italy' => ['nullable', 'string', 'max:50'],
        ]);

        $validated = $this->validate($rules);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profile updated.'));
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Flux::toast(text: __('A new verification link has been sent to your email address.'));
    }

    #[Computed]
    public function hasUnverifiedEmail(): bool
    {
        return Auth::user() instanceof MustVerifyEmail && ! Auth::user()->hasVerifiedEmail();
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Profile')" :subheading="__('Update your name, email address, and phone numbers')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>
                    </div>
                @endif
            </div>

            <flux:input
                wire:model="phone_home"
                :label="__('Home phone number')"
                type="tel"
                autocomplete="tel"
                placeholder="+61 412 345 678"
            />

            <flux:input
                wire:model="phone_italy"
                :label="__('Italy phone number')"
                type="tel"
                placeholder="+39 347 555 6789"
            />

            <div class="flex items-center gap-4">
                <button type="submit" class="button button--accent button--large" data-test="update-profile-button">
                    <span>{{ __('Save') }}</span>
                </button>
            </div>
        </form>
    </x-pages::settings.layout>
</section>
