<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Statamic\Facades\Entry;
use Statamic\Facades\Form;

new #[Title('Your enquiries')] class extends Component {
    /**
     * Booking enquiry submissions for the authenticated user, newest first.
     *
     * Each entry is a flat array of the data the view needs — tour details
     * are resolved here so the Blade template stays free of facade calls.
     *
     * @return list<array{
     *     id: string,
     *     date: \Carbon\Carbon,
     *     message: string,
     *     tour_slug: string,
     *     tour_name: string,
     *     tour_url: ?string,
     *     tour_region: string,
     *     tour_collection: ?string,
     * }>
     */
    #[Computed]
    public function enquiries(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [];
        }

        $form = Form::find('booking_enquiry');

        if (! $form) {
            return [];
        }

        return $form->submissions()
            ->filter(fn ($submission) => (string) $submission->get('user_id') === (string) $user->id)
            ->sortByDesc(fn ($submission) => $submission->date())
            ->map(function ($submission) {
                $slug = (string) $submission->get('tour');
                $tour = $slug !== ''
                    ? Entry::query()
                        ->whereIn('collection', ['guided', 'self-guided'])
                        ->where('slug', $slug)
                        ->first()
                    : null;

                return [
                    'id' => $submission->id(),
                    'date' => $submission->date(),
                    'message' => (string) $submission->get('message_body'),
                    'tour_slug' => $slug,
                    'tour_name' => (string) ($tour?->get('name') ?? $tour?->get('title') ?? $slug),
                    'tour_url' => $tour?->absoluteUrl(),
                    'tour_region' => (string) ($tour?->get('region') ?? ''),
                    'tour_collection' => $tour?->collection()?->handle(),
                ];
            })
            ->values()
            ->all();
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Your enquiries') }}</flux:heading>

    <x-pages::settings.layout
        :heading="__('Enquiries')"
        :subheading="__('A record of every booking enquiry you have sent us.')"
    >
        @if (count($this->enquiries) === 0)
            <div class="rounded-xl border border-dashed border-foreground/15 bg-card p-8 md:p-10 text-center">
                <x-lucide-send class="mx-auto mb-4 size-8 text-accent-2" stroke-width="1.5" aria-hidden="true" />
                <p class="text-base text-text mb-6">
                    {{ __("You haven't sent any enquiries yet.") }}
                </p>
                <a href="/tours" wire:navigate class="button button--accent button--large">
                    <span>{{ __('Browse our tours') }}</span>
                </a>
            </div>
        @else
            <div class="flex flex-col gap-4">
                @foreach ($this->enquiries as $enquiry)
                    <article class="bg-card border border-parchment-200 rounded-xl shadow-sm px-5 py-5 md:px-6 md:py-6">
                        <div class="flex flex-col gap-3">
                            {{-- Region + collection chip --}}
                            <div class="flex items-center justify-between gap-3">
                                @if ($enquiry['tour_region'] !== '')
                                    <span class="font-display font-bold text-sm uppercase tracking-wide text-text truncate">
                                        {{ $enquiry['tour_region'] }}
                                    </span>
                                @endif
                                @if ($enquiry['tour_collection'])
                                    <span class="shrink-0 bg-parchment-200 px-2 py-0.5 rounded-full text-[13px] text-accent-2 leading-tight">
                                        {{ $enquiry['tour_collection'] === 'guided' ? __('Guided') : __('Self-guided') }}
                                    </span>
                                @endif
                            </div>

                            {{-- Tour name (linked when we resolved the entry) --}}
                            @if ($enquiry['tour_url'])
                                <a
                                    href="{{ $enquiry['tour_url'] }}"
                                    wire:navigate
                                    class="font-display font-bold text-xl md:text-2xl leading-[1.25] md:leading-[1.5] text-foreground hover:text-accent-2"
                                >
                                    {{ $enquiry['tour_name'] }}
                                </a>
                            @else
                                <span class="font-display font-bold text-xl md:text-2xl leading-[1.25] md:leading-[1.5] text-foreground">
                                    {{ $enquiry['tour_name'] }}
                                </span>
                            @endif

                            {{-- Submitted date --}}
                            <p class="text-sm text-muted m-0" title="{{ $enquiry['date']->format('j M Y, g:i a') }}">
                                {{ __('Sent') }} {{ $enquiry['date']->diffForHumans() }}
                            </p>

                            {{-- Message excerpt --}}
                            @if ($enquiry['message'] !== '')
                                <p class="text-sm md:text-base text-text leading-[1.5] line-clamp-3 m-0">
                                    {{ $enquiry['message'] }}
                                </p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </x-pages::settings.layout>
</section>
