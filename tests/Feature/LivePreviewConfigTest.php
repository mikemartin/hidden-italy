<?php

namespace Tests\Feature;

use Tests\TestCase;

class LivePreviewConfigTest extends TestCase
{
    /**
     * Hot reload morphs the iframe DOM, which corrupts Splide carousels and
     * other Alpine components that mutate their own DOM at mount. Forcing a
     * full iframe reload keeps the carousels usable inside Live Preview.
     */
    public function test_hot_reload_contents_is_disabled_so_splide_carousels_work_in_live_preview(): void
    {
        $this->assertFalse(config('statamic.live_preview.hot_reload_contents'));
    }
}
