<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

/**
 * Auth gate for the "Make an enquiry" CTA on the tour show page.
 *
 * The component handles the email-existence check and the password
 * step inline so returning users sign in without ever leaving the
 * tour page. The only navigations are the ones that need their
 * own surface — `/register` for account creation and `/booking`
 * for the enquiry form (after successful sign-in or guest choice).
 *
 * The tour-show page button dispatches `open-booking-modal` with
 * `{tour, tourName}`; an Alpine bridge in the wrapper passes that
 * detail into `open()`.
 */
new class extends Component {
    public bool $isOpen = false;

    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public ?User $foundUser = null;

    public bool $confirming = false;

    public string $tour = '';

    public string $tourName = '';

    public function open(string $tour = '', string $tourName = ''): void
    {
        $this->reset(['email', 'password', 'remember', 'foundUser', 'confirming']);
        $this->resetValidation();
        $this->tour = $tour;
        $this->tourName = $tourName;
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->reset();
        $this->resetValidation();
    }

    /**
     * Step 1 → step 2 transition. Look the email up and either
     * show the inline password field or send the visitor off to
     * `/register` (the dedicated account-creation surface).
     */
    public function continue(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            // Unknown email — leave the modal for /register where
            // account creation actually happens. Carry the tour
            // through so the user lands back on /booking once
            // they've finished registering.
            $params = ['email' => $this->email];

            if ($this->tour !== '') {
                $params['redirect'] = '/booking?tour='.urlencode($this->tour);
            }

            $this->redirect(route('register', $params), navigate: true);

            return;
        }

        $this->foundUser = $user;
        $this->confirming = true;
    }

    public function changeEmail(): void
    {
        $this->confirming = false;
        $this->foundUser = null;
        $this->password = '';
        $this->resetValidation('password');
    }

    /**
     * Step 2 — sign the user in and route them to /booking. We
     * use Auth::attempt directly (rather than POSTing to Fortify's
     * /login.store) so a wrong password keeps the user inside the
     * modal with an inline error rather than bouncing them to
     * /login. 2FA is not supported in this surface yet.
     */
    public function login(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        $authenticated = Auth::attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember,
        );

        if (! $authenticated) {
            $this->addError('password', __('These credentials do not match our records.'));

            return;
        }

        session()->regenerate();

        $redirectUrl = $this->tour !== ''
            ? '/booking?tour='.urlencode($this->tour)
            : '/booking';

        $this->redirect($redirectUrl, navigate: true);
    }

    public function continueAsGuest(): void
    {
        $params = ['guest' => '1'];

        if ($this->tour !== '') {
            $params['tour'] = $this->tour;
        }

        if ($this->email !== '') {
            $params['email'] = $this->email;
        }

        $this->redirect('/booking?'.http_build_query($params), navigate: true);
    }

    public function firstName(): string
    {
        if (! $this->foundUser) {
            return '';
        }

        return str($this->foundUser->name)->explode(' ')->first() ?? '';
    }
}; ?>

<div
    x-data="{}"
    @open-booking-modal.window="$wire.open($event.detail?.tour ?? '', $event.detail?.tourName ?? '')"
>
    @if ($isOpen)
        <div
            x-cloak
            x-transition.opacity
            @keydown.escape.window="$wire.close()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-foreground/40"
            role="dialog"
            aria-modal="true"
            aria-labelledby="booking-modal-title"
        >
            <div
                @click.outside="$wire.close()"
                class="relative w-full max-w-md bg-background rounded-3xl shadow-xl p-8 md:p-10"
            >
                <button
                    type="button"
                    wire:click="close"
                    class="absolute right-5 top-5 inline-flex items-center justify-center text-foreground hover:text-foreground/70 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gold rounded-full"
                    aria-label="{{ __('Close') }}"
                >
                    <x-lucide-x class="size-5" stroke-width="2" aria-hidden="true" />
                </button>

                @if (! $confirming)
                    {{-- Step 1: email-first sign-in or guest. --}}
                    <h2 id="booking-modal-title" class="font-display font-bold text-xl md:text-2xl leading-[1.25] md:leading-[1.5] text-foreground text-center">
                        {{ __('strings.booking_modal_title') }}
                    </h2>

                    @if ($tourName !== '')
                        <p class="mt-2 text-base md:text-lg text-accent-2 font-medium text-center">
                            {{ $tourName }}
                        </p>
                    @endif

                    <p class="mt-4 text-sm md:text-base text-text leading-[1.5] text-center">
                        {{ __('strings.booking_modal_subtitle') }}
                    </p>

                    <form wire:submit="continue" class="mt-6 flex flex-col gap-2">
                        <label for="booking-modal-email" class="block text-base font-semibold text-foreground">
                            {{ __('strings.email_address') }}
                        </label>
                        <input
                            wire:model="email"
                            type="email"
                            id="booking-modal-email"
                            required
                            autocomplete="email"
                            placeholder="{{ __('strings.email_placeholder') }}"
                            x-init="$nextTick(() => $el.focus())"
                            class="block w-full rounded-full bg-card px-5 py-3 text-base text-foreground border border-text/20 placeholder:text-text/40 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition"
                        />
                        @error('email')
                            <p class="text-accent-2 text-sm">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="button button--accent button--large w-full mt-3">
                            <span>{{ __('strings.continue_action') }}</span>
                        </button>
                    </form>

                    <div class="my-6 flex items-center gap-4 text-sm text-text">
                        <span class="flex-1 h-px bg-foreground/10"></span>
                        <span class="uppercase tracking-[0.04em]">{{ __('strings.or') }}</span>
                        <span class="flex-1 h-px bg-foreground/10"></span>
                    </div>

                    <button
                        type="button"
                        wire:click="continueAsGuest"
                        class="button button--large w-full bg-parchment-200 text-foreground hover:bg-parchment-300"
                    >
                        <span>{{ __('strings.continue_as_guest') }}</span>
                    </button>
                @else
                    {{-- Step 2: inline password for known email. --}}
                    <h2 id="booking-modal-title" class="font-display font-bold text-xl md:text-2xl leading-[1.25] md:leading-[1.5] text-foreground text-center">
                        {{ __('Welcome back, :name', ['name' => $this->firstName()]) }}
                    </h2>

                    <p class="mt-4 text-sm md:text-base text-text leading-[1.5] text-center">
                        {{ __('Enter your password to continue your enquiry.') }}
                    </p>

                    <div class="mt-6 flex items-center justify-between gap-3 p-3 border rounded-lg border-foreground/10">
                        <div class="min-w-0">
                            <div class="text-sm font-medium truncate text-foreground">{{ $foundUser->name }}</div>
                            <div class="text-xs truncate text-text">{{ $foundUser->email }}</div>
                        </div>
                        <button
                            type="button"
                            wire:click="changeEmail"
                            class="text-sm cursor-pointer shrink-0 underline text-foreground hover:text-accent-2"
                        >
                            {{ __('Change') }}
                        </button>
                    </div>

                    <form wire:submit="login" class="mt-4 flex flex-col gap-4">
                        <flux:input
                            wire:model="password"
                            :label="__('Password')"
                            type="password"
                            required
                            autofocus
                            autocomplete="current-password"
                            viewable
                        />

                        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

                        <button type="submit" class="button button--accent button--large w-full mt-2">
                            <span>{{ __('Sign in') }}</span>
                        </button>
                    </form>

                    @if (Route::has('password.request'))
                        <div class="mt-4 text-center text-sm">
                            <a href="{{ route('password.request') }}" wire:navigate class="text-foreground underline hover:text-accent-2">
                                {{ __('Forgot your password?') }}
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
