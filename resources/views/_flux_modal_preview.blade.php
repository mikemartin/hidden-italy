{{--
    Flux Modal preview — booking enquiry gate.
    Used by /flux-components as a reference for the booking-enquiry
    auth modal that lives inline in tours/show.antlers.html.

    Note: Flux modals teleport to a top-level portal, so the modal
    body always renders in the page's surface mode regardless of any
    `.dark` ancestor in the trigger's container. The trigger button
    itself does pick up the surrounding mode.

    @var $id  Unique suffix so each preview instance has its own
              modal name (Flux requires unique names per page).
--}}
@php($id ??= 'preview')

<flux:modal.trigger name="booking-{{ $id }}">
    <flux:button variant="primary" icon:trailing="arrow-right">Request a booking</flux:button>
</flux:modal.trigger>

<flux:modal name="booking-{{ $id }}" class="md:w-[32rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Request a booking</flux:heading>
            <flux:text class="mt-2">
                Sign in to send your enquiry, or continue as a guest and
                we&rsquo;ll create an account from your details.
            </flux:text>
        </div>

        <div class="flex flex-col gap-3">
            <flux:button variant="primary" class="w-full">Sign in to continue</flux:button>
            <flux:button variant="outline" class="w-full">Continue as guest</flux:button>
        </div>

        <flux:text size="sm" class="text-muted">
            By continuing you agree to our terms and privacy policy.
        </flux:text>
    </div>
</flux:modal>
