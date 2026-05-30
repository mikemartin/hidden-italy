<?php

namespace App\Imaging;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use League\Glide\Manipulators\BaseManipulator;

/**
 * Nordic — a custom Glide manipulator that applies a HALD CLUT (the LUT
 * extracted and validated against Canva's Nordic filter) to an image using
 * Imagick's native haldClutImage().
 *
 * Why this shape:
 *  - Statamic 6's Glide runs on Intervention Image v3, whose high-level API has
 *    no hald-clut operation, so we reach the native \Imagick instance via
 *    $image->core()->native() (verified: Imagick\Core::native() returns the
 *    underlying Imagick object) and call haldClutImage() directly.
 *  - Requires the Imagick driver (GD has no CLUT support). We guard for it and
 *    skip cleanly rather than failing the render.
 *  - The LUT ships WITH the code (config('nordic.lut_path')), never an
 *    author-managed asset, so it cannot be deleted or tinted by accident.
 *
 * Opt-in trigger:  {{ glide:img filter="nordic" }}
 *  Statamic maps the friendly `filter` tag param to Glide's short `filt` key,
 *  so this manipulator keys off `filt` — NOT `filter`. Glide's built-in Filter
 *  manipulator treats `filt=nordic` as a no-op (it only knows greyscale/sepia),
 *  so the two coexist and no param allow-listing is required.
 *
 * Registration is wired into Glide's Server singleton in a service provider.
 */
class NordicFilter extends BaseManipulator
{
    /**
     * Absolute path to the processed HALD PNG (the verified hald_nordic.png).
     */
    protected string $lutPath;

    /**
     * Optional explicit intensity override (constructor-injected, for tests).
     */
    protected ?int $intensityOverride;

    public function __construct(?string $lutPath = null, ?int $intensity = null)
    {
        $this->lutPath = $lutPath ?? config('nordic.lut_path', resource_path('luts/hald_nordic.png'));
        $this->intensityOverride = $intensity;
    }

    /**
     * Param keys this manipulator consumes.
     *
     * BaseManipulator::setParams() discards every param whose key is not
     * returned here, so `filt` MUST be listed or run() would never see the
     * trigger. `intensity` is listed so a debug-only URL override can reach us.
     *
     * @return list<string>
     */
    public function getApiParams(): array
    {
        return ['filt', 'intensity'];
    }

    /**
     * Glide calls run() for every manipulation. We only act when our trigger is
     * present, so non-filtered images pass straight through (this is opt-in).
     */
    public function run(ImageInterface $image): ImageInterface
    {
        if ($this->getParam('filt') !== 'nordic') {
            return $image;
        }

        if (! extension_loaded('imagick')) {
            logger()->warning('NordicFilter skipped: Imagick extension not available.');

            return $image;
        }

        if (! is_file($this->lutPath)) {
            logger()->warning("NordicFilter skipped: LUT missing at {$this->lutPath}");

            return $image;
        }

        $native = $image->core()->native();

        if (! $native instanceof Imagick) {
            logger()->warning('NordicFilter skipped: not running the Imagick driver.');

            return $image;
        }

        $intensity = $this->resolveIntensity();

        if ($intensity <= 0) {
            return $image;
        }

        $this->applyToNative($native, $intensity);

        return $image;
    }

    /**
     * Resolve the grade intensity (0-100). Config is the source of truth and is
     * brand-locked. An `intensity` param override is honoured for TESTING only
     * (when app.debug is on); in production the param is ignored so the look
     * stays consistent no matter what query string is thrown at the route.
     */
    public function resolveIntensity(): int
    {
        $intensity = $this->intensityOverride
            ?? ($this->debugOverrideEnabled() ? $this->getParam('intensity') : null)
            ?? config('nordic.intensity', 100);

        return max(0, min(100, (int) $intensity));
    }

    /**
     * Apply the HALD CLUT to a native Imagick instance, in place.
     *
     * Extracted from run() so the imaging core can be exercised outside Glide
     * (see the nordic:proof command). At full strength the CLUT is applied
     * directly; below that, the graded clone is composited over the original
     * at the requested opacity so shadows/highlights are preserved.
     */
    public function applyToNative(Imagick $native, int $intensity): void
    {
        $clut = new Imagick($this->lutPath);

        try {
            if ($intensity >= 100) {
                $native->haldClutImage($clut);

                return;
            }

            $filtered = clone $native;
            $filtered->haldClutImage($clut);

            $this->setWholeImageAlpha($filtered, $intensity / 100);

            $native->compositeImage($filtered, Imagick::COMPOSITE_DISSOLVE, 0, 0);

            $filtered->clear();
            $filtered->destroy();
        } finally {
            $clut->clear();
            $clut->destroy();
        }
    }

    /**
     * Set whole-image alpha (0..1, 1 = opaque) for the DISSOLVE composite.
     *
     * The method name differs across ImageMagick versions: IM7 exposes
     * setImageAlpha(); IM6 only has the (deprecated-in-7) setImageOpacity().
     * Detect and use whichever exists rather than assuming a version.
     */
    protected function setWholeImageAlpha(Imagick $image, float $alpha): void
    {
        if (method_exists($image, 'setImageAlpha')) {
            $image->setImageAlpha($alpha);

            return;
        }

        $image->setImageOpacity($alpha);
    }

    protected function debugOverrideEnabled(): bool
    {
        return (bool) config('app.debug', false);
    }
}
