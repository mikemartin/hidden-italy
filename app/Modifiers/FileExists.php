<?php

namespace App\Modifiers;

use Statamic\Modifiers\Modifier;

class FileExists extends Modifier
{
    /**
     * Check whether a file path exists on disk, resolved against the
     * application's base path. Used by the page-builder audit page
     * so screenshot status pills auto-detect when an editor drops a
     * file into `public/page_builder/` (or anywhere else) rather
     * than relying on hardcoded `image_status="present"` overrides.
     *
     * Usage in Antlers: `{{ 'public/page_builder/cards.png' | file_exists }}`.
     */
    public function index($value): bool
    {
        if (! is_string($value) || $value === '') {
            return false;
        }

        return file_exists(base_path($value));
    }
}
