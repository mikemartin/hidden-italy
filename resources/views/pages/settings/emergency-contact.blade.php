<?php

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Emergency contact')] class extends Component {
    public string $emergency_contact_name = '';

    public string $emergency_contact_email = '';

    public string $emergency_contact_phone = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->emergency_contact_name = (string) ($user->emergency_contact_name ?? '');
        $this->emergency_contact_email = (string) ($user->emergency_contact_email ?? '');
        $this->emergency_contact_phone = (string) ($user->emergency_contact_phone ?? '');
    }

    /**
     * Save the emergency contact information.
     */
    public function save(): void
    {
        $validated = $this->validate([
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_email' => ['nullable', 'string', 'email', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
        ]);

        Auth::user()->fill($validated)->save();

        Flux::toast(variant: 'success', text: __('Emergency contact updated.'));
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Emergency contact') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Emergency contact')" :subheading="__('A trusted contact we can alert in an urgent situation.')">
        <form wire:submit="save" class="my-6 w-full space-y-6">
            <flux:input
                wire:model="emergency_contact_name"
                :label="__('Name')"
                type="text"
                autocomplete="name"
            />

            <flux:input
                wire:model="emergency_contact_email"
                :label="__('Email address')"
                type="email"
                autocomplete="email"
            />

            <flux:input
                wire:model="emergency_contact_phone"
                :label="__('Phone number')"
                type="tel"
                autocomplete="tel"
            />

            <div class="flex items-center gap-4">
                <button type="submit" class="button button--accent button--large" data-test="update-emergency-contact-button">
                    <span>{{ __('Save') }}</span>
                </button>
            </div>
        </form>
    </x-pages::settings.layout>
</section>
