<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LegacyRedirectTest extends TestCase
{
    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function redirectProvider(): array
    {
        return [
            // Simple path redirects.
            'guided tours landing' => ['/guided-tours', '/tours/guided'],
            'self-guided landing' => ['/self-guided-walks', '/tours/self-guided'],
            'contact us' => ['/contact-us', '/contact'],
            'book now' => ['/booking/book-now.aspx', '/booking'],
            'news' => ['/whats-on/news', '/inspiration'],
            'about us' => ['/about-us/default.aspx', '/about'],
            'why choose us to about anchor' => ['/why-choose-us/default.aspx', '/about#why-tour-with-us'],
            'our people to about anchor' => ['/our-people/default.aspx', '/about#our-people'],
            'ready to go' => ['/ready-to-go', '/tips'],
            'faqs' => ['/faqs', '/contact'],
            'australasia' => ['/australasia', '/'],

            // Query-string redirects switch on the parameter.
            'guided tour by id' => ['/guided-tours/page.aspx?p=45', '/tours/guided/campania-and-the-amalfi-coast'],
            'self-guided by id' => ['/self-guided-walks/page.aspx?p=22', '/tours/self-guided/tuscany2'],
            'blog post by id' => ['/whats-on/blog/details.aspx?a=16', '/inspiration/the-moorish-delicacies-of-palermo'],
            'footer privacy app' => ['/footer/default.aspx?p=34', '/privacy-app'],
            'footer terms' => ['/footer/default.aspx?p=14', '/terms'],

            // Unknown query values fall back to the section landing page.
            'unknown guided id falls back' => ['/guided-tours/page.aspx?p=999', '/tours/guided'],
            'unknown footer id falls back' => ['/footer/default.aspx?p=999', '/'],
        ];
    }

    #[DataProvider('redirectProvider')]
    public function test_legacy_url_redirects_permanently(string $from, string $to): void
    {
        $this->get($from)
            ->assertStatus(301)
            ->assertRedirect($to);
    }

    public function test_social_links_redirect_to_external_profiles(): void
    {
        $this->get('/whats-on/page.aspx?p=25')
            ->assertStatus(301)
            ->assertRedirect('https://www.facebook.com/hidden.italy.walking.tours');

        $this->get('/whats-on/page.aspx?p=32')
            ->assertStatus(301)
            ->assertRedirect('https://www.instagram.com/hiddenitalywalkingtours');
    }
}
