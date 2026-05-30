<?php

namespace Tests\Feature;

use App\Imaging\ImageFilter;
use League\Glide\Manipulators\Filter;
use League\Glide\Server;
use Tests\TestCase;

class ImageFilterRegistrationTest extends TestCase
{
    public function test_image_filter_is_registered_on_the_glide_server(): void
    {
        $manipulators = collect(app(Server::class)->getApi()->getManipulators());

        $this->assertTrue(
            $manipulators->contains(fn ($m) => $m instanceof ImageFilter),
            'ImageFilter should be registered on the Glide server.',
        );
    }

    public function test_image_filter_runs_after_glides_built_in_filter(): void
    {
        // Built-in Filter no-ops on our named filters, so ours must come later
        // in the pipeline to actually apply the grade.
        $manipulators = collect(app(Server::class)->getApi()->getManipulators());

        $builtInFilter = $manipulators->search(fn ($m) => $m instanceof Filter);
        $imageFilter = $manipulators->search(fn ($m) => $m instanceof ImageFilter);

        $this->assertNotFalse($builtInFilter);
        $this->assertNotFalse($imageFilter);
        $this->assertGreaterThan($builtInFilter, $imageFilter);
    }

    public function test_each_filter_produces_a_distinct_cache_path(): void
    {
        // Every named filter must cache to its own entry (originals/plain
        // variants untouched), exactly like any other Glide param.
        $server = app(Server::class);
        $path = 'some/photo.jpg';

        $plain = $server->getCachePath($path, ['w' => 800]);
        $nordic = $server->getCachePath($path, ['w' => 800, 'filt' => 'nordic']);
        $fresco = $server->getCachePath($path, ['w' => 800, 'filt' => 'fresco']);

        $this->assertNotSame($plain, $nordic);
        $this->assertNotSame($plain, $fresco);
        $this->assertNotSame($nordic, $fresco);
    }

    public function test_nordic_and_fresco_are_registered_filters(): void
    {
        $filter = new ImageFilter;

        $this->assertIsArray($filter->filterConfig('nordic'));
        $this->assertIsArray($filter->filterConfig('fresco'));
    }
}
