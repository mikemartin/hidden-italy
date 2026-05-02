{{--
    Enquiry-sent pill — small "Enquiry sent · 5 days ago" indicator
    rendered on a tour card when the authenticated user has at least
    one booking enquiry on file for that tour. Renders nothing for
    guests, for users with no matching enquiry, or when the form
    isn't installed.

    Used from the wishlist (`/wishlist`) so logged-in users can tell
    at a glance which liked tours they've already enquired about.
    Lookup is scoped to the current user and the tour's slug.

    @var string $slug  The tour entry's slug.
--}}
@php
    use Illuminate\Support\Facades\Auth;
    use Statamic\Facades\Form;

    $latestEnquiry = null;
    $user = Auth::user();

    if ($user && ! empty($slug)) {
        $latestEnquiry = Form::find('booking_enquiry')
            ?->submissions()
            ->filter(fn ($submission) =>
                (string) $submission->get('user_id') === (string) $user->id
                && (string) $submission->get('tour') === (string) $slug
            )
            ->sortByDesc(fn ($submission) => $submission->date())
            ->first();
    }
@endphp

@if ($latestEnquiry)
    <div class="mb-2">
        <span class="inline-flex items-center gap-1.5 rounded-full bg-gold/15 text-foreground text-[12px] font-medium px-2.5 py-1">
            <x-lucide-send class="size-3" stroke-width="2" aria-hidden="true" />
            {{ __('Enquiry sent') }} · {{ $latestEnquiry->date()->diffForHumans() }}
        </span>
    </div>
@endif
