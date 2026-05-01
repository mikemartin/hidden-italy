<?php

use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Postal address')] class extends Component {
    public string $postal_country = 'Australia';

    public string $postal_street = '';

    public string $postal_city = '';

    public string $postal_state = '';

    public string $postcode = '';

    /**
     * Country options.
     *
     * @var array<int, string>
     */
    public array $countries = [
        'Australia',
        'Canada',
        'Italy',
        'New Zealand',
        'United Kingdom',
        'United States',
    ];

    /**
     * Australian state/territory options.
     *
     * @var array<int, string>
     */
    public array $australianStates = [
        'ACT',
        'NSW',
        'NT',
        'QLD',
        'SA',
        'TAS',
        'VIC',
        'WA',
    ];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $user = Auth::user();

        $this->postal_country = (string) ($user->postal_country ?? 'Australia');
        $this->postal_street = (string) ($user->postal_street ?? '');
        $this->postal_city = (string) ($user->postal_city ?? '');
        $this->postal_state = (string) ($user->postal_state ?? '');
        $this->postcode = (string) ($user->postcode ?? '');
    }

    /**
     * Save the postal address.
     */
    public function save(): void
    {
        $validated = $this->validate([
            'postal_country' => ['required', 'string', Rule::in($this->countries)],
            'postal_street' => ['nullable', 'string', 'max:255'],
            'postal_city' => ['nullable', 'string', 'max:255'],
            'postal_state' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:20'],
        ]);

        Auth::user()->fill($validated)->save();

        Flux::toast(variant: 'success', text: __('Postal address updated.'));
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Postal address') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Postal address')" :subheading="__('Enter your address so we can send you your travel pack.')">
        <form wire:submit="save" class="my-6 w-full space-y-6">
            <flux:select wire:model.live="postal_country" :label="__('Country')" required>
                @foreach ($countries as $country)
                    <flux:select.option :value="$country">{{ $country }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model="postal_street"
                :label="__('Street address')"
                type="text"
                autocomplete="street-address"
            />

            <flux:input
                wire:model="postal_city"
                :label="__('City / Suburb')"
                type="text"
                autocomplete="address-level2"
            />

            <div class="grid grid-cols-2 gap-4">
                @if ($postal_country === 'Australia')
                    <flux:select wire:model="postal_state" :label="__('State')" :placeholder="__('Select state')">
                        @foreach ($australianStates as $state)
                            <flux:select.option :value="$state">{{ $state }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @else
                    <flux:input
                        wire:model="postal_state"
                        :label="__('State / Region')"
                        type="text"
                        autocomplete="address-level1"
                    />
                @endif

                <flux:input
                    wire:model="postcode"
                    :label="__('Postcode')"
                    type="text"
                    autocomplete="postal-code"
                />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="button button--accent button--large" data-test="update-postal-address-button">
                    <span>{{ __('Save') }}</span>
                </button>
            </div>
        </form>
    </x-pages::settings.layout>
</section>
