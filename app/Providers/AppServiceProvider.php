<?php

namespace App\Providers;

use App\Fieldtypes\BookingStatus;
use App\Policies\CustomUserPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Statamic\Events\SubmissionCreated;
use Statamic\Facades\Form;
use Statamic\Facades\Icon;
use Statamic\Policies\UserPolicy;
use Statamic\Statamic;
use Studio1902\PeakSeo\Handlers\ErrorPage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserPolicy::class, CustomUserPolicy::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load resources/js/cp.js into the Statamic CP — registers
        // custom Vue fieldtype components (see cp.js for the list).
        // Resolved through Vite via the `resources/js/cp.js` input
        // declared in vite.config.js.
        Statamic::script('app', 'cp');

        // Custom fieldtypes
        BookingStatus::register();

        ErrorPage::handle404AsEntry();

        Icon::register('wencory', base_path('public/icons'));

        Livewire::forceAssetInjection();

        $this->bootRoute();

        $this->bootFormConfig();

        $this->bootBookingEnquiryFlash();
    }

    /**
     * Flash the just-submitted booking enquiry's email and name to
     * the session so the /booking/thank-you page can offer guest
     * visitors one-click account creation pre-filled with their own
     * details. Logged-in submissions are ignored — the prompt is
     * guest-only.
     */
    private function bootBookingEnquiryFlash(): void
    {
        Event::listen(SubmissionCreated::class, function (SubmissionCreated $event) {
            $submission = $event->submission;

            if ($submission->form()?->handle() !== 'booking_enquiry') {
                return;
            }

            session()->flash('booking_enquiry_email', (string) $submission->get('email'));
            session()->flash('booking_enquiry_name', (string) $submission->get('name'));
        });
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    public function bootFormConfig(): void
    {
        Form::appendConfigFields('*', __('Custom form sender text'), [
            'content_sender' => [
                'full_width_setting' => true,
                'type' => 'group',
                'display' => '',
                'fullscreen' => false,
                'border' => false,
                'fields' => [
                    ['import' => 'form_email_config'],
                ],
            ],
        ]);

        Form::appendConfigFields('*', __('Custom form owner text'), [
            'content_owner' => [
                'full_width_setting' => true,
                'type' => 'group',
                'display' => '',
                'fullscreen' => false,
                'border' => false,
                'fields' => [
                    ['import' => 'form_email_config'],
                ],
            ],
        ]);
    }
}
