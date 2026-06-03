<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Image Filters
    |--------------------------------------------------------------------------
    |
    | Each entry is a brand colour grade applied on render through a HALD CLUT,
    | opt-in per image with {{ glide:img filter="<name>" }}.
    |
    |  - lut_path:  absolute path to a version-controlled 512x512 level-8 HALD
    |               CLUT PNG (shipped with the code in resources/luts, never an
    |               author-managed asset).
    |  - intensity: locked 0-100 strength applied site-wide. 100 = full grade;
    |               anything lower blends the graded image over the original.
    |               Brand-locked and intentionally NOT author-configurable.
    |
    | Add a filter by dropping its LUT in resources/luts and adding one entry.
    |
    */

    'filters' => [

        'nordic' => [
            'lut_path' => resource_path('luts/hald_nordic.png'),
            'intensity' => (int) env('NORDIC_FILTER_INTENSITY', 100),
        ],

        'fresco' => [
            'lut_path' => resource_path('luts/hald_fresco.png'),
            'intensity' => (int) env('FRESCO_FILTER_INTENSITY', 100),
        ],

    ],

];
