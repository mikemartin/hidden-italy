{{--
    Flux Accordion preview — sample tour FAQs.
    Used by /flux-components ahead of swapping the FAQ page-builder
    block (page_builder/_faqs.antlers.html) over to flux:accordion.

    @var $id  Unique suffix so multiple instances on the same page have
              distinct IDs internally.
--}}
@php($id ??= 'preview')

<flux:accordion transition>
    <flux:accordion.item heading="What's the typical group size?" expanded>
        Most departures run with 6&ndash;12 walkers, plus your local
        guide. We cap small-group tours at 14 so the table stays
        sociable and the trail stays uncrowded.
    </flux:accordion.item>

    <flux:accordion.item heading="Do I need previous walking experience?">
        A reasonable level of fitness is enough for our easier grades.
        For Grade 4&ndash;5 routes we recommend a few months of regular
        hill walking in the lead-up so the longer ascents feel
        comfortable.
    </flux:accordion.item>

    <flux:accordion.item heading="What does the price include?">
        All accommodation on a twin-share basis, daily breakfasts,
        most dinners, luggage transfers between stays, your local
        guide and any included tastings or entry fees. Lunches and
        flights are excluded.
    </flux:accordion.item>
</flux:accordion>
