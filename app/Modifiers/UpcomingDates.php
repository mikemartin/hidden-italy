<?php

namespace App\Modifiers;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Statamic\Modifiers\Modifier;

class UpcomingDates extends Modifier
{
    /**
     * Filter an array of dates to those on or after today, sorted ascending.
     * Past dates are dropped. Accepts Carbon instances or parseable date
     * strings — matches the augmentation of Statamic's `date` fieldtype in
     * `mode: multiple`, used for tour `departures`.
     *
     * Usage in Antlers:
     * ```
     * {{ departures | upcoming_dates }}
     *     {{ value | format:'j F Y' }}
     * {{ /departures }}
     * ```
     *
     * @param  iterable<int, CarbonInterface|string|null>|null  $value
     * @return array<int, CarbonInterface>
     */
    public function index($value): array
    {
        if (! is_iterable($value)) {
            return [];
        }

        $today = Carbon::today();

        return collect($value)
            ->map(fn ($v) => $v instanceof CarbonInterface ? $v : Carbon::parse($v))
            ->filter(fn (CarbonInterface $date) => $date->greaterThanOrEqualTo($today))
            ->sort()
            ->values()
            ->all();
    }
}
