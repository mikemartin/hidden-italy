<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Locks in the project's `formHandler` registration in `resources/js/site.js`.
 *
 * Peak Tools' upstream `_form_handler.antlers.html` snippet registers
 * `Alpine.data('formHandler', …)` inside an `alpine:initializing` listener
 * emitted inline on each form page. That event only fires once per browser
 * session — on the initial page load — so visitors who reach a form page via
 * Livewire's `wire:navigate` (any nav click into /contact, the tour CTA
 * into /booking, …) attach the listener too late, `formHandler` never
 * registers, the form's `x-data="formHandler()"` evaluates to `undefined`,
 * the subtree fails to hydrate, and the visitor sees only the static
 * submit button.
 *
 * Registering the factory in `site.js` instead means it lands in Alpine's
 * persistent data registry on the first page load and survives every
 * subsequent `wire:navigate`. We deliberately don't override Peak's
 * vendor partial so upstream upgrades flow through untouched.
 */
class FormHandlerSnippetTest extends TestCase
{
    private string $siteJsPath = __DIR__.'/../../resources/js/site.js';

    public function test_site_js_exists(): void
    {
        $this->assertFileExists($this->siteJsPath);
    }

    public function test_site_js_registers_form_handler_via_alpine_data(): void
    {
        $contents = file_get_contents($this->siteJsPath);

        $this->assertMatchesRegularExpression(
            "/Alpine\.data\(\s*['\"]formHandler['\"]/",
            $contents,
            'site.js must register `formHandler` via `Alpine.data` so the factory persists across wire:navigate transitions.'
        );
    }

    public function test_form_handler_is_registered_on_alpine_init(): void
    {
        $contents = file_get_contents($this->siteJsPath);

        $initBlock = $this->extractAlpineInitBlock($contents);

        $this->assertNotNull(
            $initBlock,
            'site.js must keep its `alpine:init` listener.'
        );

        $this->assertMatchesRegularExpression(
            "/Alpine\.data\(\s*['\"]formHandler['\"]/",
            $initBlock,
            'The `formHandler` registration must live inside the `alpine:init` listener so it runs before Alpine processes any x-data on the first load.'
        );
    }

    public function test_form_handler_exposes_the_expected_api(): void
    {
        $contents = file_get_contents($this->siteJsPath);

        foreach (['init()', 'submit()', 'successHook(', 'this.$form(', 'this.$refs.form'] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $contents,
                "site.js's `formHandler` factory must keep the `{$needle}` API that the form templates rely on."
            );
        }
    }

    public function test_form_handler_handles_captcha_response(): void
    {
        $contents = file_get_contents($this->siteJsPath);

        $this->assertStringContainsString(
            'captcha-response',
            $contents,
            'site.js must forward the captcha response token at submit time.'
        );

        $this->assertStringContainsString(
            'cf-turnstile-response',
            $contents,
            'site.js must look up the Turnstile response input (the configured captcha service in config/captcha.php).'
        );
    }

    public function test_peak_vendor_override_is_not_in_place(): void
    {
        $overridePath = __DIR__.'/../../resources/views/vendor/statamic-peak-tools/snippets/_form_handler.antlers.html';

        $this->assertFileDoesNotExist(
            $overridePath,
            'Peak Tools\' `_form_handler.antlers.html` must not be overridden — shadowing it silently freezes the upstream snippet across upgrades.'
        );
    }

    private function extractAlpineInitBlock(string $contents): ?string
    {
        if (! preg_match("/addEventListener\(\s*['\"]alpine:init['\"]\s*,\s*\(\)\s*=>\s*\{/", $contents, $match, PREG_OFFSET_CAPTURE)) {
            return null;
        }

        $start = $match[0][1] + strlen($match[0][0]);
        $depth = 1;
        $length = strlen($contents);

        for ($i = $start; $i < $length; $i++) {
            $char = $contents[$i];
            if ($char === '{') {
                $depth++;
            } elseif ($char === '}') {
                $depth--;
                if ($depth === 0) {
                    return substr($contents, $start, $i - $start);
                }
            }
        }

        return null;
    }
}
