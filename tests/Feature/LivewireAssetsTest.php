<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Features\SupportAutoInjectedAssets\SupportAutoInjectedAssets;
use Livewire\Livewire;
use Mikomagni\SimpleLikes\ServiceProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LivewireAssetsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * The simple-likes addon auto-creates its table on web requests only
     * (auto-migration is skipped in console, where tests run), so create
     * it here using the addon's own schema.
     */
    protected function setUp(): void
    {
        // Reset before the app boots in parent::setUp() so the boot-time
        // injection test below measures this app's boot, not whatever an
        // earlier test in the same process left behind.
        SupportAutoInjectedAssets::$forceAssetInjection = false;

        parent::setUp();

        $provider = $this->app->getProvider(ServiceProvider::class);

        (new \ReflectionMethod($provider, 'createSimpleLikesTable'))->invoke($provider, null);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function pageProvider(): array
    {
        return [
            'home (no Livewire components)' => ['/'],
            'tour listing (Livewire filter components)' => ['/tours/guided'],
        ];
    }

    /**
     * Livewire's JS bundles Alpine, so a second evaluation of livewire.js
     * surfaces in the browser as "Detected multiple instances of
     * Livewire/Alpine running". The layout renders the assets manually
     * (livewire.inject_assets is off) so every page — including statically
     * cached copies — carries exactly one copy.
     */
    #[DataProvider('pageProvider')]
    public function test_page_includes_livewire_assets_exactly_once(string $url): void
    {
        $html = $this->get($url)->assertOk()->getContent();

        $this->assertLivewireAssetsIncludedOnce($html);
    }

    public function test_statically_cached_response_includes_livewire_assets_exactly_once(): void
    {
        config(['statamic.static_caching.strategy' => 'half']);

        $miss = $this->get('/tours/guided')->assertOk()->getContent();

        // Mimic the fresh PHP-FPM process a real cache hit gets: reset
        // Livewire's per-request state, which otherwise survives between
        // requests in the test process and masks hit-time asset injection.
        Livewire::flushState();

        $hit = $this->get('/tours/guided')->assertOk()->getContent();

        $this->assertLivewireAssetsIncludedOnce($miss);
        $this->assertLivewireAssetsIncludedOnce($hit);
    }

    /**
     * A static-cache hit renders no views, so Livewire's "assets already
     * rendered" dedupe flag is unset for the whole request. If anything in
     * the app's boot path calls Livewire::forceAssetInjection(), the
     * RequestHandled auto-injector appends a second livewire.js to the
     * cached HTML — booting a second Alpine and breaking every store and
     * Alpine.data registration. The layout renders the assets manually, so
     * nothing may globally force injection.
     */
    public function test_app_boot_does_not_force_livewire_asset_injection(): void
    {
        $this->assertFalse(
            SupportAutoInjectedAssets::$forceAssetInjection,
            'Something in the app boot path calls Livewire::forceAssetInjection(), which duplicates livewire.js on static-cache hits.'
        );
    }

    protected function assertLivewireAssetsIncludedOnce(string $html): void
    {
        $scriptCount = preg_match_all('/<script[^>]*src="[^"]*livewire[^"]*"/', $html);

        $this->assertSame(1, $scriptCount, 'Expected exactly one livewire.js script tag.');
        $this->assertSame(1, substr_count($html, '<!-- Livewire Styles -->'), 'Expected exactly one Livewire styles block.');
    }
}
