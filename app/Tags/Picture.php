<?php

namespace App\Tags;

use Studio1902\PeakTools\Tags\Picture as PeakPicture;

class Picture extends PeakPicture
{
    /**
     * Project-level override of Peak's `{{ picture }}` tag.
     *
     * Peak emits `<img loading="lazy">` for lazy images but never sets
     * `decoding` or `fetchpriority`, so the browser decodes every
     * lazy image synchronously on the main thread the moment it
     * enters the viewport. On a tour show page with ~30 lazy
     * pictures, that's the dominant cause of scroll-time frame
     * drops (verified against a DevTools Performance trace —
     * ImageDecodeTask events line up with every DroppedFrame
     * cluster).
     *
     * Two enhancements applied here, both honouring Peak's existing
     * `lazy` param (default `true`):
     *
     * - lazy=true  → append `decoding="async" fetchpriority="low"` so
     *                the decode runs off the main thread; scroll stays
     *                smooth.
     * - lazy=false → inject `fetchpriority="high"` so the LCP / hero
     *                candidate is actually prioritised. Decoding is
     *                left at the browser default (`auto`) so the
     *                hero paints in step with surrounding content.
     */
    public function index(): string
    {
        return $this->enhanceImg(parent::index());
    }

    public function wildcard(string $tag): string
    {
        return $this->enhanceImg(parent::wildcard($tag));
    }

    protected function enhanceImg(string $html): string
    {
        $lazy = $this->params->bool('lazy', true);

        if ($lazy) {
            return preg_replace(
                '/(\sloading="lazy")/',
                '$1 decoding="async" fetchpriority="low"',
                $html
            );
        }

        // Eager (LCP / hero): inject fetchpriority="high" right
        // after the opening `<img ` so the browser prioritises the
        // fetch. Only matches the fallback img — `<source>` elements
        // don't take fetchpriority.
        return preg_replace(
            '/(<img\s)/',
            '$1fetchpriority="high" ',
            $html,
            1
        );
    }
}
