<?php

namespace App\Providers;

use App\Imaging\NordicFilter;
use App\Policies\CustomUserPolicy;
use App\Tags\Picture;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use JackSleight\StatamicBardMutator\Facades\Mutator;
use League\Glide\Server;
use Livewire\Livewire;
use Statamic\Facades\Form;
use Statamic\Facades\Icon;
use Statamic\Policies\UserPolicy;
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

        $this->registerNordicGlideManipulator();
    }

    /**
     * Append the Nordic HALD CLUT manipulator to Statamic's Glide server.
     *
     * Statamic binds League\Glide\Server as a singleton (built via
     * Glide::server()). We extend that binding and push our manipulator onto
     * the API's existing list — the same get-api / setManipulators / set-api
     * dance Statamic itself uses to swap the watermark manipulator. Appending
     * last means the grade is applied after sizing/cropping, and Glide's
     * built-in Filter (which no-ops on filt=nordic) still runs harmlessly
     * before it.
     */
    private function registerNordicGlideManipulator(): void
    {
        $this->app->extend(Server::class, function (Server $server) {
            $api = $server->getApi();

            $api->setManipulators(
                collect($api->getManipulators())->push(new NordicFilter)->all()
            );

            $server->setApi($api);

            return $server;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Statamic::script('app', 'cp');
        // Statamic::style('app', 'cp');

        ErrorPage::handle404AsEntry();

        Icon::register('wencory', base_path('public/icons'));
        Icon::register('lucide', base_path('resources/svg/lucide'));

        Livewire::forceAssetInjection();

        Mutator::html('heading', function ($value, $item) {
            if ($item->attrs->level === 2) {
                $value[1]['id'] = Str::slug(collect($item->content)->implode('text', ''));
            }

            return $value;
        });

        $this->bootRoute();

        $this->bootFormConfig();

        // Re-register our Picture tag override AFTER all providers
        // have booted — `app/Tags/` auto-discovery happens before
        // addon service providers register their tags, so we'd
        // otherwise lose the binding to Peak's tag. The `booted`
        // callback runs once everything is up, last-write wins.
        $this->app->booted(fn () => Picture::register());
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
