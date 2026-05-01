@php
    $accountNav = [
        ['route' => 'account.profile',           'label' => __('Profile'),           'icon' => 'circle-user-round'],
        ['route' => 'account.password',          'label' => __('Password'),          'icon' => 'key-round'],
        ['route' => 'account.emergency-contact', 'label' => __('Emergency Contact'), 'icon' => 'ambulance'],
        ['route' => 'account.postal-address',    'label' => __('Postal Address'),    'icon' => 'mailbox'],
    ];
@endphp

<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}">
            @foreach ($accountNav as $item)
                <flux:navlist.item :href="route($item['route'])" wire:navigate>
                    <x-slot name="icon">
                        <x-dynamic-component :component="'lucide-' . $item['icon']" class="size-5" stroke-width="1.75" />
                    </x-slot>
                    {{ $item['label'] }}
                </flux:navlist.item>
            @endforeach
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        @if (! empty($heading))
            <h2 class="font-display font-bold text-2xl md:text-4xl leading-[1.25] tracking-[0.01em] md:tracking-[-0.01em] text-foreground">
                {{ $heading }}
            </h2>
        @endif

        @if (! empty($subheading))
            <p class="mt-3 text-base md:text-lg font-normal leading-[1.5] md:leading-[1.6] text-text">
                {{ $subheading }}
            </p>
        @endif

        <div class="mt-8 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
