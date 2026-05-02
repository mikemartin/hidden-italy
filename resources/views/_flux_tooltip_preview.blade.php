{{--
    Flux Tooltip preview — trail stat hints.
    Used by /flux-components ahead of layering tooltips onto the
    distance / elevation / grade row in the Experience block.
--}}

<div class="flex flex-wrap items-center gap-3">
    <flux:tooltip content="Total distance covered across the full itinerary.">
        <flux:button icon="footprints" variant="subtle">62 km</flux:button>
    </flux:tooltip>

    <flux:tooltip content="Total elevation gain over the trip." position="bottom">
        <flux:button icon="mountain" variant="subtle">1,840 m</flux:button>
    </flux:tooltip>

    <flux:tooltip content="Difficulty grading on our 1&ndash;5 scale, where 5 is the most demanding.">
        <flux:button icon="gauge" variant="subtle">Grade 3 / 5</flux:button>
    </flux:tooltip>

    <flux:tooltip toggleable>
        <flux:button icon="information-circle" variant="ghost" size="sm" aria-label="More info" />
        <flux:tooltip.content class="max-w-[20rem]">
            <p>Toggleable tooltips are useful for touch devices &mdash; they open on tap rather than hover.</p>
        </flux:tooltip.content>
    </flux:tooltip>
</div>
