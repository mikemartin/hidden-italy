<?php

namespace Tests\Feature;

use Tests\TestCase;

class LivePreviewConfigTest extends TestCase
{
    /**
     * Hot reload morphs the iframe DOM, which clobbers Livewire's wire:id and
     * wire:snapshot state and leaves the tour filter components inert. Keeping
     * this disabled forces a full iframe reload so Livewire mounts cleanly.
     */
    public function test_hot_reload_contents_is_disabled_so_livewire_works_in_live_preview(): void
    {
        $this->assertFalse(config('statamic.live_preview.hot_reload_contents'));
    }
}
