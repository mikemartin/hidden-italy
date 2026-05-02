{{--
    Flux Modal flyout preview — full bio for the Tour Leader block.
    Used by /flux-components ahead of swapping the external "Read full
    bio" link in page_builder/_leader.antlers.html for an in-page
    flyout so visitors stay on the tour page.

    @var $id  Unique suffix per instance.
--}}
@php($id ??= 'preview')

<flux:modal.trigger name="bio-{{ $id }}">
    <flux:button variant="ghost" icon:trailing="arrow-up-right">Read full bio</flux:button>
</flux:modal.trigger>

<flux:modal name="bio-{{ $id }}" variant="flyout" class="md:w-[34rem]">
    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <div class="size-20 shrink-0 rounded-xl bg-parchment-200 flex items-center justify-center">
                <flux:icon.user class="size-10 text-accent-2" />
            </div>
            <div>
                <flux:heading size="lg">Marco Rossi</flux:heading>
                <flux:text class="!mt-1 text-accent-2 font-medium">
                    Tour Leader &middot; Tuscany &amp; Umbria
                </flux:text>
            </div>
        </div>

        <flux:separator />

        <div class="space-y-4 font-sans text-base leading-[1.6] text-text">
            <p>
                Marco grew up in a small village in the Chianti hills and
                has been guiding walkers through central Italy for over
                fifteen years. He trained as an archaeologist before
                turning his attention full-time to the trails, and his
                tours weave the region&rsquo;s layered history into every
                day on foot.
            </p>
            <p>
                When he&rsquo;s not leading a tour you&rsquo;ll find him
                tending the family olive grove, foraging for porcini in
                the autumn woods, or somewhere on a long-distance
                pilgrim path with his border collie, Bea.
            </p>
            <p>
                Marco speaks Italian, English and conversational French,
                and is a qualified Wilderness First Responder.
            </p>
        </div>

        <flux:separator />

        <div class="flex justify-end">
            <flux:modal.close>
                <flux:button variant="primary">Close</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
