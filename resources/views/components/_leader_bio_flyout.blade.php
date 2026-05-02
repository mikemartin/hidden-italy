{{--
    Leader bio flyout — "Read bio" trigger plus a side flyout
    rendering the linked person's portrait, name, subtitle and bio.
    Included from page_builder/_leader.antlers.html and inherits the
    person scope.

    Renders nothing when the person has no `short_bio`, so people
    entries without a bio yet don't surface a dead-end action.

    Available scope: $title, $subtitle, $short_bio, $image, $slug.
--}}
@php
    $bio = trim((string) ($short_bio ?? ''));
    $modalName = 'leader-bio-' . ($slug ?? 'leader');
@endphp

@if($bio !== '')
    <flux:modal.trigger name="{{ $modalName }}">
        <button
            type="button"
            class="inline-flex items-center gap-1.5 mt-2 font-display font-medium text-base text-foreground underline underline-offset-4 decoration-gold hover:decoration-foreground"
        >
            <span>{{ __('strings.read_bio') }}</span>
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

            <div class="font-sans text-base leading-[1.6] text-text">
                <p>{{ $bio }}</p>
            </div>
        </div>
    </flux:modal>
@endif
