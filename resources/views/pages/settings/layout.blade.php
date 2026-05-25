@php
    $accountNav = [
        ['route' => 'account.profile',  'label' => __('Profile'),  'icon' => 'circle-user-round'],
        ['route' => 'account.password', 'label' => __('Password'), 'icon' => 'key-round'],
    ];

    // Flux's auto data-current detection is unreliable inside Livewire
    // update requests (it depends on app.url matching the request scheme).
    // Computing the active path manually keeps the nav highlight in sync
    // after wire:submit form saves.
    $currentPath = '/'.trim(
        app('livewire')?->isLivewireRequest()
            ? app('livewire')->originalPath()
            : request()->path(),
        '/'
    );
@endphp

<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}">
            @foreach ($accountNav as $item)
                @php $itemPath = '/'.trim(parse_url(route($item['route']), PHP_URL_PATH), '/'); @endphp
                <flux:navlist.item :href="route($item['route'])" :current="$currentPath === $itemPath" wire:navigate>
                    <x-slot name="icon">
                        <x-dynamic-component :component="'lucide-' . $item['icon']" class="size-5" stroke-width="1.75" />
                    </x-slot>
                    {{ $item['label'] }}
                </flux:navlist.item>
            @endforeach

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:navlist.item as="button" type="submit" class="w-full">
                    <x-slot name="icon">
                        <x-lucide-log-out class="size-5" stroke-width="1.75" />
                    </x-slot>
                    {{ __('Log out') }}
                </flux:navlist.item>
            </form>
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
