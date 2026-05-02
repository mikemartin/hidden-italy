{{--
    Profile card — for tour leaders and team members from the People
    collection. Image / name / role / "Read bio" trigger that opens a
    flux:modal flyout with the full bio. Sized for 4-column layouts —
    do not render in wider grids.

    Inherits the People entry scope: $title, $subtitle, $short_bio,
    $image, $slug.
--}}
@php
    $bio = trim((string) ($short_bio ?? ''));
    $modalName = 'profile-bio-' . ($slug ?? 'profile');
@endphp

<article class="group relative isolate h-full flex flex-col bg-card border border-parchment-200 rounded-xl shadow-sm overflow-hidden">
    @if($bio !== '')
        <flux:modal.trigger name="{{ $modalName }}">
            <button
                type="button"
                class="absolute inset-0 z-10 rounded-xl focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold"
            >
                <span class="sr-only">Read {{ $title }}&rsquo;s bio</span>
            </button>
        </flux:modal.trigger>
    @endif

    @if(!empty($image))
        <div class="relative p-1">
            <div class="relative w-full aspect-[4/3] rounded-lg overflow-hidden bg-parchment-200">
                <s:picture
                    :image="$image"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105"
                    cover="true"
                    sizes="(min-width: 768px) 400px, 100vw"
                    loading="lazy"
                />
            </div>
        </div>
    @endif

    <div class="flex-1 flex flex-col px-5 py-6">
        <s:partial src="typography/h6" small="true" :content="$title" color="text-foreground" />
        @if(!empty($subtitle))
            <s:partial src="typography/p_small" :content="$subtitle" color="text-muted" class="!mb-0" />
        @endif
        @if($bio !== '')
            <span class="relative z-20 mt-3 inline-flex items-center gap-1.5 font-display font-semibold text-base text-foreground underline underline-offset-4 decoration-gold group-hover:decoration-foreground">
                {{ __('strings.read_bio') }}
                <x-lucide-arrow-right class="size-4 text-accent-2" stroke-width="2" aria-hidden="true" />
            </span>
        @endif
    </div>
</article>

@if($bio !== '')
    <flux:modal name="{{ $modalName }}" variant="flyout" class="md:w-[34rem]">
        <div class="space-y-6">
            <div class="flex items-center gap-4">
                @if(!empty($image))
                    <div class="size-20 shrink-0 rounded-xl overflow-hidden bg-parchment-200">
                        <s:picture :image="$image" class="w-full h-full object-cover" cover="true" sizes="80px" loading="lazy" />
                    </div>
                @endif
                <div>
                    <flux:heading size="lg">{{ $title }}</flux:heading>
                    @if(!empty($subtitle))
                        <flux:text class="!mt-1 text-accent-2 font-medium">{{ $subtitle }}</flux:text>
                    @endif
                </div>
            </div>

            <flux:separator />

            <div class="font-sans text-base leading-[1.6] text-text">
                <p>{{ $bio }}</p>
            </div>
        </div>
    </flux:modal>
@endif
