{{--
    Flux Tabs preview — Walk / Accommodation / Food.
    Used by /flux-components to demo the Tabs component in light and
    dark modes ahead of swapping the custom tab UI in
    page_builder/_experience.antlers.html.

    @var $id  Unique suffix so multiple instances on the same page have
              distinct tab names (Flux requires unique `name` values).
--}}
@php($id ??= 'preview')

<flux:tab.group>
    <flux:tabs default="walk-{{ $id }}">
        <flux:tab name="walk-{{ $id }}" icon="map">Walk</flux:tab>
        <flux:tab name="accommodation-{{ $id }}" icon="home-modern">Accommodation</flux:tab>
        <flux:tab name="food-{{ $id }}" icon="cake">Food</flux:tab>
    </flux:tabs>

    <flux:tab.panel name="walk-{{ $id }}">
        <div class="font-sans text-base md:text-lg leading-[1.6] text-text">
            <p>
                Daily distances of 8&ndash;14&thinsp;km along well-marked paths,
                with luggage transferred between stays so you walk with just a
                day pack.
            </p>
        </div>
    </flux:tab.panel>

    <flux:tab.panel name="accommodation-{{ $id }}">
        <div class="font-sans text-base md:text-lg leading-[1.6] text-text">
            <p>
                Family-run agriturismi and historic stone hotels chosen for
                their welcome, local cooking and genuine sense of place.
            </p>
        </div>
    </flux:tab.panel>

    <flux:tab.panel name="food-{{ $id }}">
        <div class="font-sans text-base md:text-lg leading-[1.6] text-text">
            <p>
                Long lunches of regional pasta, hand-rolled gnocchi, garden
                vegetables and bottles from the nearest hill &mdash; the
                kitchen is half the trip.
            </p>
        </div>
    </flux:tab.panel>
</flux:tab.group>
