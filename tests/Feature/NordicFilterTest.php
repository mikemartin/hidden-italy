<?php

namespace Tests\Feature;

use App\Imaging\NordicFilter;
use Intervention\Image\Interfaces\ImageInterface;
use Mockery;
use Tests\TestCase;

class NordicFilterTest extends TestCase
{
    public function test_it_exposes_filt_and_intensity_as_api_params(): void
    {
        $this->assertSame(['filt', 'intensity'], (new NordicFilter)->getApiParams());
    }

    public function test_it_passes_non_nordic_images_straight_through(): void
    {
        // No `filt=nordic` trigger: the image must be returned untouched and the
        // native Imagick instance must never be reached.
        $image = Mockery::mock(ImageInterface::class);
        $image->shouldNotReceive('core');

        $filter = (new NordicFilter)->setParams(['filt' => 'greyscale']);

        $this->assertSame($image, $filter->run($image));
    }

    public function test_it_skips_cleanly_when_imagick_is_unavailable(): void
    {
        if (extension_loaded('imagick')) {
            $this->markTestSkipped('Imagick is loaded; this asserts the missing-extension fallback.');
        }

        // Triggered, but Imagick absent: returns the image without touching core().
        $image = Mockery::mock(ImageInterface::class);
        $image->shouldNotReceive('core');

        $filter = (new NordicFilter)->setParams(['filt' => 'nordic']);

        $this->assertSame($image, $filter->run($image));
    }

    public function test_intensity_defaults_to_locked_config_value(): void
    {
        config()->set('nordic.intensity', 80);

        $this->assertSame(80, (new NordicFilter)->resolveIntensity());
    }

    public function test_intensity_is_clamped_to_0_100(): void
    {
        $this->assertSame(100, (new NordicFilter(intensity: 150))->resolveIntensity());
        $this->assertSame(0, (new NordicFilter(intensity: -5))->resolveIntensity());
    }

    public function test_constructor_intensity_overrides_config(): void
    {
        config()->set('nordic.intensity', 100);

        $this->assertSame(40, (new NordicFilter(intensity: 40))->resolveIntensity());
    }

    public function test_intensity_param_override_is_honoured_only_in_debug(): void
    {
        config()->set('nordic.intensity', 100);

        config()->set('app.debug', true);
        $debug = (new NordicFilter)->setParams(['filt' => 'nordic', 'intensity' => '30']);
        $this->assertSame(30, $debug->resolveIntensity());

        config()->set('app.debug', false);
        $prod = (new NordicFilter)->setParams(['filt' => 'nordic', 'intensity' => '30']);
        $this->assertSame(100, $prod->resolveIntensity(), 'Param override must be ignored in production.');
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
