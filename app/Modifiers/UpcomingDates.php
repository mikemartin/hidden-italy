<?php

namespace App\Modifiers;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Statamic\Modifiers\Modifier;

class UpcomingDates extends Modifier
{
    /**
     * Filter an iterable of dates to those on or after today, sorted
     * ascending. Past dates are dropped. Accepts Carbon instances, parseable
     * date strings, or Grid rows shaped as `['date' => Carbon|string]` —
     * matching the augmented shape of the tour `departures` grid.
     *
     * Usage in Antlers:
     * ```
     * {{ departures | upcoming_dates }}
     *     {{ value | format:'j F Y' }}
     * {{ /departures }}
     * ```
     *
     * @return array<int, CarbonInterface>
     */
    public function index($value): array
    {
        if (! is_iterable($value)) {
            return [];
        }

        $today = Carbon::today();

        return collect($value)
            ->map(fn ($v) => $this->extractDate($v))
            ->filter()
            ->filter(fn (CarbonInterface $date) => $date->greaterThanOrEqualTo($today))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Coerce a value into a Carbon instance, unwrapping Grid rows. Grid
     * rows arrive as either plain arrays or `Statamic\Fields\Values`
     * (ArrayAccess) when augmented through the front end.
     */
    private function extractDate(mixed $value): ?CarbonInterface
    {
        if (is_array($value) || $value instanceof \ArrayAccess) {
            $value = $value['date'] ?? null;
        }

        if ($value instanceof CarbonInterface) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            return Carbon::parse($value);
        }

        return null;
    }
}
