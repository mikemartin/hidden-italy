<?php

namespace App\Imaging;

use Imagick;
use Intervention\Image\Interfaces\ImageInterface;
use League\Glide\Manipulators\BaseManipulator;

/**
 * ClutFilter — a config-driven Glide manipulator that applies a named brand
 * colour grade via a HALD CLUT, using Imagick's native haldClutImage().
 *
 * Filters are registered in config/image_filters.php; each maps a name to a
 * version-controlled LUT PNG and a locked intensity. A single instance handles
 * every named filter, so adding one is "drop a LUT + add a config line".
 *
 * Why this shape:
 *  - Statamic 6's Glide runs on Intervention Image v3, whose high-level API has
 *    no hald-clut operation, so we reach the native \Imagick instance via
 *    $image->core()->native() and call haldClutImage() directly.
 *  - Requires the Imagick driver (GD has no CLUT support). We guard for it and
 *    skip cleanly rather than failing the render.
 *  - LUTs ship WITH the code, never as author-managed assets, so a look cannot
 *    be deleted or tinted by accident.
 *
 * Opt-in trigger:  {{ glide:img filter="nordic" }}  /  filter="fresco"
 *  Statamic maps the friendly `filter` tag param to Glide's short `filt` key,
 *  so this manipulator keys off `filt`. Values that aren't registered filters
 *  (e.g. greyscale/sepia handled by Glide's built-in Filter) pass straight
 *  through, so the two coexist without any param allow-listing.
 */
class ClutFilter extends BaseManipulator
{
    /**
     * Param keys this manipulator consumes.
     *
     * BaseManipulator::setParams() discards any param key not returned here, so
     * `filt` MUST be listed or run() would never see the trigger. `intensity`
     * is listed so a debug-only URL override can reach us.
     *
     * @return list<string>
     */
    public function getApiParams(): array
    {
        return ['filt', 'intensity'];
    }

    /**
     * Glide calls run() for every manipulation. We only act when `filt` names a
     * registered filter, so everything else passes through (this is opt-in).
     */
    public function run(ImageInterface $image): ImageInterface
    {
        $name = $this->getParam('filt');
        $filter = $this->filterConfig($name);

        if ($filter === null) {
            return $image;
        }

        if (! extension_loaded('imagick')) {
            logger()->warning("ClutFilter [{$name}] skipped: Imagick extension not available.");

            return $image;
        }

        if (! is_file($filter['lut_path'])) {
            logger()->warning("ClutFilter [{$name}] skipped: LUT missing at {$filter['lut_path']}");

            return $image;
        }

        $native = $image->core()->native();

        if (! $native instanceof Imagick) {
            logger()->warning("ClutFilter [{$name}] skipped: not running the Imagick driver.");

            return $image;
        }

        $intensity = $this->resolveIntensity($filter['intensity'] ?? 100);

        if ($intensity <= 0) {
            return $image;
        }

        $this->applyClut($native, $filter['lut_path'], $intensity);

        return $image;
    }

    /**
     * Resolve a registered filter's config by name, or null if unknown.
     *
     * @return array{lut_path:string,intensity?:int}|null
     */
    public function filterConfig(?string $name): ?array
    {
        if ($name === null || $name === '') {
            return null;
        }

        $filter = config("image_filters.filters.{$name}");

        return is_array($filter) ? $filter : null;
    }

    /**
     * Resolve the grade intensity (0-100). The configured value is brand-locked.
     * An `intensity` param override is honoured for TESTING only (app.debug on);
     * in production the param is ignored so the look stays consistent.
     */
    public function resolveIntensity(int $configured): int
    {
        $intensity = ($this->debugOverrideEnabled() ? $this->getParam('intensity') : null) ?? $configured;

        return max(0, min(100, (int) $intensity));
    }

    /**
     * Apply a HALD CLUT to a native Imagick instance, in place.
     *
     * Extracted from run() so it can be exercised outside Glide (see the
     * clut:proof command). At full strength the CLUT is applied directly; below
     * that, the graded clone is composited over the original at the requested
     * opacity so shadows/highlights are preserved.
     */
    public function applyClut(Imagick $native, string $lutPath, int $intensity): void
    {
        $clut = new Imagick($lutPath);

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
