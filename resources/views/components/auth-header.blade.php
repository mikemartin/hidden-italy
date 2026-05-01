@props([
    'title',
    'description' => null,
])

<div class="flex w-full flex-col gap-3 text-center">
    <h1 class="font-display font-bold text-2xl md:text-4xl leading-[1.25] tracking-[0.01em] md:tracking-[-0.01em] text-foreground">
        {{ $title }}
    </h1>

    @if ($description)
        <p class="text-base md:text-lg font-normal leading-[1.5] md:leading-[1.6] text-text">
            {{ $description }}
        </p>
    @endif
</div>
