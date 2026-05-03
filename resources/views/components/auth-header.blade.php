@props([
    'title',
    'description' => null,
])

<div class="flex w-full flex-col gap-3 text-center">
    <h1 class="font-display font-bold text-xl md:text-2xl leading-[1.25] md:leading-[1.5] text-foreground">
        {{ $title }}
    </h1>

    @if ($description)
        <p class="text-sm md:text-base font-normal leading-[1.5] text-text">
            {{ $description }}
        </p>
    @endif
</div>
