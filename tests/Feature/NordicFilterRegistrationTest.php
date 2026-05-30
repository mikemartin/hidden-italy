<?php

namespace Tests\Feature;

use App\Imaging\NordicFilter;
use League\Glide\Manipulators\Filter;
use League\Glide\Server;
use Tests\TestCase;

class NordicFilterRegistrationTest extends TestCase
{
    public function test_nordic_filter_is_registered_on_the_glide_server(): void
    {
        $manipulators = collect(app(Server::class)->getApi()->getManipulators());

        $this->assertTrue(
            $manipulators->contains(fn ($m) => $m instanceof NordicFilter),
            'NordicFilter should be registered on the Glide server.',
        );
    }

    public function test_nordic_filter_runs_after_glides_built_in_filter(): void
    {
        // Built-in Filter no-ops on filt=nordic, so ours must come later in the
        // pipeline to actually apply the grade.
        $manipulators = collect(app(Server::class)->getApi()->getManipulators());

        $builtInFilter = $manipulators->search(fn ($m) => $m instanceof Filter);
        $nordic = $manipulators->search(fn ($m) => $m instanceof NordicFilter);

        $this->assertNotFalse($builtInFilter);
        $this->assertNotFalse($nordic);
        $this->assertGreaterThan($builtInFilter, $nordic);
    }

    public function test_nordic_param_produces_a_distinct_cache_path(): void
    {
        // filt=nordic must cache to its own entry (originals/plain variants
        // untouched), exactly like any other Glide param.
        $server = app(Server::class);
        $path = 'some/photo.jpg';

        $plain = $server->getCachePath($path, ['w' => 800]);
        $nordic = $server->getCachePath($path, ['w' => 800, 'filt' => 'nordic']);

        $this->assertNotSame($plain, $nordic);
    }
}
