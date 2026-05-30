<?php

namespace Tests\Feature;

use App\Imaging\ImageFilter;
use Intervention\Image\Interfaces\ImageInterface;
use Mockery;
use Tests\TestCase;

class ImageFilterTest extends TestCase
{
    public function test_it_exposes_filt_and_intensity_as_api_params(): void
    {
        $this->assertSame(['filt', 'intensity'], (new ImageFilter)->getApiParams());
    }

    public function test_it_resolves_registered_filters_and_ignores_unknown_ones(): void
    {
        config()->set('image_filters.filters', [
            'nordic' => ['lut_path' => '/luts/hald_nordic.png', 'intensity' => 100],
        ]);

        $filter = new ImageFilter;

        $this->assertIsArray($filter->filterConfig('nordic'));
        $this->assertNull($filter->filterConfig('greyscale'), 'Built-in Glide filters must pass through.');
        $this->assertNull($filter->filterConfig(null));
        $this->assertNull($filter->filterConfig(''));
    }

    public function test_it_passes_unregistered_filt_values_straight_through(): void
    {
        // e.g. greyscale/sepia handled by Glide's built-in Filter: untouched,
        // and the native Imagick instance is never reached.
        $image = Mockery::mock(ImageInterface::class);
        $image->shouldNotReceive('core');

        $filter = (new ImageFilter)->setParams(['filt' => 'greyscale']);

        $this->assertSame($image, $filter->run($image));
    }

    public function test_it_skips_cleanly_when_imagick_is_unavailable(): void
    {
        if (extension_loaded('imagick')) {
            $this->markTestSkipped('Imagick is loaded; this asserts the missing-extension fallback.');
        }

        config()->set('image_filters.filters.nordic', ['lut_path' => '/luts/hald_nordic.png', 'intensity' => 100]);

        $image = Mockery::mock(ImageInterface::class);
        $image->shouldNotReceive('core');

        $filter = (new ImageFilter)->setParams(['filt' => 'nordic']);

        $this->assertSame($image, $filter->run($image));
    }

    public function test_intensity_uses_the_configured_locked_value(): void
    {
        $this->assertSame(80, (new ImageFilter)->resolveIntensity(80));
    }

    public function test_intensity_is_clamped_to_0_100(): void
    {
        $this->assertSame(100, (new ImageFilter)->resolveIntensity(150));
        $this->assertSame(0, (new ImageFilter)->resolveIntensity(-5));
    }

    public function test_intensity_param_override_is_honoured_only_in_debug(): void
    {
        config()->set('app.debug', true);
        $debug = (new ImageFilter)->setParams(['filt' => 'nordic', 'intensity' => '30']);
        $this->assertSame(30, $debug->resolveIntensity(100));

        config()->set('app.debug', false);
        $prod = (new ImageFilter)->setParams(['filt' => 'nordic', 'intensity' => '30']);
        $this->assertSame(100, $prod->resolveIntensity(100), 'Param override must be ignored in production.');
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
