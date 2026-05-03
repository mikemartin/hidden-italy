<?php

namespace App\Fieldtypes;

use Statamic\Fieldtypes\Select;

/**
 * Booking enquiry status — Open / Booked / Closed.
 *
 * Extends Statamic's stock Select so the CP edit UI is the standard
 * dropdown (no Vue work needed there). Adds a custom Vue index
 * component (registered as `booking_status-fieldtype-index` in
 * resources/js/cp.js) that renders a coloured status pill in the
 * submissions listing — visual triage at a glance.
 *
 * Options are hardcoded here rather than declared in the blueprint
 * so every consumer of the field uses the same three states.
 */
class BookingStatus extends Select
{
    protected static $title = 'Booking Status';

    protected static $handle = 'booking_status';

    protected $categories = ['special'];

    protected $icon = 'list';

    /**
     * Hide the standard Select config tab (options, multiple,
     * searchable, etc.) — the field is purpose-built and shouldn't
     * be reconfigured per use.
     */
    protected function configFieldItems(): array
    {
        return [];
    }

    /**
     * Push the hardcoded options + sensible defaults into the data
     * the Vue edit component receives, so the blueprint only needs
     * `type: booking_status` with no further setup.
     */
    public function preload(): array
    {
        $preloaded = parent::preload();

        $preloaded['options'] = [
            'open' => 'Open',
            'booked' => 'Booked',
            'closed' => 'Closed',
        ];
        $preloaded['default'] = 'open';
        $preloaded['clearable'] = false;
        $preloaded['multiple'] = false;
        $preloaded['searchable'] = false;
        $preloaded['taggable'] = false;

        return $preloaded;
    }

    /**
     * Default missing values to "open" so the pill renders even on
     * legacy submissions filed before the field was added.
     */
    public function preProcessIndex($data)
    {
        return (string) ($data ?: 'open');
    }
}
