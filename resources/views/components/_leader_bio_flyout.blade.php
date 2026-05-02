{{--
    Leader bio flyout — "Read full bio" trigger plus a side flyout
    rendering the linked person's portrait, name, subtitle and full
    bio. Included from page_builder/_leader.antlers.html and inherits
    the person scope.

    Renders nothing unless a `bio` (or, as a fallback, `short_bio`)
    is available on the person, so authors who haven't filled in a
    long-form bio yet don't surface a dead-end trigger.

    Available scope: $title, $subtitle, $short_bio, $bio (parsed
    markdown HTML), $image, $slug.
--}}
@php
    $bio = trim((string) ($bio ?? ''));
    $shortBio = trim((string) ($short_bio ?? ''));
    $hasBio = $bio !== '' || $shortBio !== '';
    $modalName = 'leader-bio-' . ($slug ?? 'leader');
@endphp

@if($hasBio)
    <flux:modal.trigger name="{{ $modalName }}">
        <button
            type="button"
            class="inline-flex items-center gap-1.5 mt-2 font-display font-medium text-base text-foreground underline underline-offset-4 decoration-gold hover:decoration-foreground"
        >
            <span>{{ __('strings.read_full_bio') }}</span>
            <x-lucide-arrow-up-right class="size-4" stroke-width="1.75" aria-hidden="true" />
        </button>
    </flux:modal.trigger>

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

            <div class="font-sans text-base leading-[1.6] text-text [&_p]:mb-4 [&_p:last-child]:mb-0 [&_ul]:list-disc [&_ul]:ml-5 [&_ul]:mb-4 [&_a]:text-foreground [&_a]:underline [&_a]:decoration-gold">
                @if($bio !== '')
                    {!! $bio !!}
                @else
                    <p>{{ $shortBio }}</p>
                @endif
            </div>
        </div>
    </flux:modal>
@endif
