<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
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
        $hit = $this->get('/tours/guided')->assertOk()->getContent();

        $this->assertLivewireAssetsIncludedOnce($miss);
        $this->assertLivewireAssetsIncludedOnce($hit);
    }

    protected function assertLivewireAssetsIncludedOnce(string $html): void
    {
        $scriptCount = preg_match_all('/<script[^>]*src="[^"]*livewire[^"]*"/', $html);

        $this->assertSame(1, $scriptCount, 'Expected exactly one livewire.js script tag.');
        $this->assertSame(1, substr_count($html, '<!-- Livewire Styles -->'), 'Expected exactly one Livewire styles block.');
    }
}
