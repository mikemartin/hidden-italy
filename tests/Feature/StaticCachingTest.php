<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mikomagni\SimpleLikes\ServiceProvider;
use PHPUnit\Framework\Attributes\DataProvider;
use Statamic\StaticCaching\Cacher;
use Tests\TestCase;

class StaticCachingTest extends TestCase
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

        config(['statamic.static_caching.strategy' => 'half']);

        app(Cacher::class)->flush();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function sessionSpecificUrlProvider(): array
    {
        return [
            'booking form' => ['/booking'],
            'booking thank-you' => ['/booking/thank-you'],
            'wishlist' => ['/wishlist'],
            'login' => ['/login'],
            'register' => ['/register'],
            'forgot password' => ['/forgot-password'],
            'account area' => ['/account/profile'],
        ];
    }

    #[DataProvider('sessionSpecificUrlProvider')]
    public function test_session_specific_urls_are_excluded_from_static_caching(string $path): void
    {
        $cacher = app(Cacher::class);

        $this->assertTrue(
            $cacher->isExcluded($cacher->getBaseUrl().$path),
            "Expected {$path} to be excluded from static caching."
        );
    }

    /**
     * The account item in the header varies by auth state. The first
     * visitor's markup gets baked into the statically cached page, so the
     * region is wrapped in {{ nocache }} to re-render per request — a
     * logged-in user served a cache hit must see their own name, not the
     * guest "Account" link.
     */
    public function test_account_nav_varies_by_auth_on_statically_cached_pages(): void
    {
        $base = app(Cacher::class)->getBaseUrl();

        $this->get($base.'/')->assertOk()->assertSee('>Account<', false);

        $this->assertTrue(
            app(Cacher::class)->hasCachedPage(Request::create($base.'/')),
            'Expected the home page to be statically cached after the first request.'
        );

        $user = User::create([
            'name' => 'Giulia Verdi',
            'email' => 'giulia@example.com',
            'password' => 'password123',
        ]);

        $this->actingAs($user)
            ->get($base.'/')
            ->assertOk()
            ->assertSee('Giulia Verdi');
    }
}
