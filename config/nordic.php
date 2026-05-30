<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Nordic Filter Intensity
    |--------------------------------------------------------------------------
    |
    | The locked, site-wide strength of the Nordic colour grade, 0-100.
    | 100 applies the HALD CLUT at full strength; anything lower blends the
    | graded image over the original at this opacity. This is a brand-locked
    | value — it is intentionally NOT author-configurable in the Control Panel.
    | Tune the real value with the designer, then commit it here.
    |
    */

    'intensity' => (int) env('NORDIC_FILTER_INTENSITY', 100),

    /*
    |--------------------------------------------------------------------------
    | HALD CLUT LUT Path
    |--------------------------------------------------------------------------
    |
    | Absolute path to the version-controlled HALD CLUT PNG. This ships WITH
    | the code (never an author-managed asset) so the brand look cannot be
    | deleted or altered by accident. To change the look later, replace this
    | file, run `php please glide:clear`, then re-warm the cache.
    |
    */

    'lut_path' => resource_path('luts/hald_nordic.png'),

];
