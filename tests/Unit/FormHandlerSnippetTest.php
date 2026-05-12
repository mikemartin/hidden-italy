<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Locks in the project override of `statamic-peak-tools::snippets/form_handler`.
 *
 * The upstream snippet registers `Alpine.data('formHandler', …)` inside a
 * `document.addEventListener('alpine:initializing', …)` callback. That event
 * only fires once on the initial page load — so when a visitor reaches a
 * form page via Livewire's `wire:navigate` (e.g. clicking "Book this tour"
 * from a tour page, or hitting "Contact" in the main nav), the listener is
 * attached too late and `formHandler` is never registered. The form's
 * `x-data="formHandler()"` then evaluates to `undefined`, the subtree fails
 * to hydrate, and the visitor sees only the static submit button.
 *
 * The override at
 * `resources/views/vendor/statamic-peak-tools/snippets/_form_handler.antlers.html`
 * registers immediately when Alpine is already live and re-runs
 * `Alpine.initTree` on any form wrapper whose data stack is missing the
 * `submit` method, covering the morph race where the wire:navigate'd
 * x-data is processed before the inline script runs.
 */
class FormHandlerSnippetTest extends TestCase
{
    private string $overridePath = __DIR__.'/../../resources/views/vendor/statamic-peak-tools/snippets/_form_handler.antlers.html';

    public function test_override_exists(): void
    {
        $this->assertFileExists($this->overridePath);
    }

    public function test_override_does_not_rely_on_alpine_initializing_event(): void
    {
        $contents = file_get_contents($this->overridePath);

        $this->assertDoesNotMatchRegularExpression(
            '/addEventListener\(\s*[\'"]alpine:initializing[\'"]/',
            $contents,
            'The override must not register on `alpine:initializing` — that event only fires on the initial page load and is missed on every wire:navigate.'
        );
    }

    public function test_override_registers_immediately_when_alpine_is_live(): void
    {
        $contents = file_get_contents($this->overridePath);

        $this->assertMatchesRegularExpression(
            '/if\s*\(\s*window\.Alpine\s*\)\s*\{\s*register\(\)/',
            $contents,
            'The override must register `formHandler` immediately when Alpine is already running (the wire:navigate case).'
        );

        $this->assertStringContainsString(
            "document.addEventListener('alpine:init', register)",
            $contents,
            'The override must still register on `alpine:init` for the cold first-page load.'
        );
    }

    public function test_override_rehydrates_unbound_forms(): void
    {
        $contents = file_get_contents($this->overridePath);

        $this->assertStringContainsString(
            '[x-data^="formHandler"]',
            $contents,
            'The override must look up existing formHandler-wrapped elements so it can re-init any that were processed before registration.'
        );
        $this->assertStringContainsString(
            'Alpine.initTree',
            $contents,
            'The override must call Alpine.initTree on form wrappers whose data stack is missing the formHandler API.'
        );
    }

    public function test_override_preserves_upstream_form_api(): void
    {
        $contents = file_get_contents($this->overridePath);

        foreach (['init()', 'submit()', 'successHook(response)', 'this.$form(', 'this.$refs.form'] as $needle) {
            $this->assertStringContainsString(
                $needle,
                $contents,
                "The override must keep the upstream `{$needle}` API so existing form templates keep working."
            );
        }
    }
}
