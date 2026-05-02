@php
    // Top-level account areas — the user's activity. Listed first so
    // the headline reasons to hold an account are the first things in
    // the nav.
    $primaryNav = [
        ['href' => route('account.enquiries'), 'label' => __('Enquiries'), 'icon' => 'send', 'navigate' => true],
        ['href' => '/wishlist',                'label' => __('Wishlist'),  'icon' => 'heart', 'navigate' => true],
    ];

    // Settings sub-grouping — profile and account-management forms.
    $settingsNav = [
        ['route' => 'account.profile',           'label' => __('Profile'),           'icon' => 'circle-user-round'],
        ['route' => 'account.password',          'label' => __('Password'),          'icon' => 'key-round'],
        ['route' => 'account.emergency-contact', 'label' => __('Emergency Contact'), 'icon' => 'ambulance'],
        ['route' => 'account.postal-address',    'label' => __('Postal Address'),    'icon' => 'mailbox'],
    ];
@endphp

<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Account') }}">
            @foreach ($primaryNav as $item)
                <flux:navlist.item :href="$item['href']" wire:navigate>
                    <x-slot name="icon">
                        <x-dynamic-component :component="'lucide-' . $item['icon']" class="size-5" stroke-width="1.75" />
                    </x-slot>
                    {{ $item['label'] }}
                </flux:navlist.item>
            @endforeach

            <flux:navlist.group :heading="__('Settings')" class="mt-2">
                @foreach ($settingsNav as $item)
                    <flux:navlist.item :href="route($item['route'])" wire:navigate>
                        <x-slot name="icon">
                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="size-5" stroke-width="1.75" />
                        </x-slot>
                        {{ $item['label'] }}
                    </flux:navlist.item>
                @endforeach
            </flux:navlist.group>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        @if (! empty($heading))
            <h2 class="font-display font-bold text-xl md:text-2xl leading-[1.25] md:leading-[1.5] text-foreground">
                {{ $heading }}
            </h2>
        @endif

        @if (! empty($subheading))
            <p class="mt-3 text-sm md:text-base font-normal leading-[1.5] text-text">
                {{ $subheading }}
            </p>
        @endif

        <div class="mt-8 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
