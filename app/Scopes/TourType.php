<?php

namespace App\Scopes;

use Statamic\Facades\Entry;
use Statamic\Query\Builder;
use Statamic\Query\Scopes\Filter;

class TourType extends Filter
{
    /**
     * Pin the filter.
     *
     * @var bool
     */
    public $pinned = true;

    public static function title()
    {
        return __('Tour Type');
    }

    /**
     * Narrow the listing on /tours to a single tour collection.
     *
     * Driven by the `tour-type-filter` Livewire component, which dispatches
     * its selection under the param key `tour_type:filter_tour_type`
     * (modifier:field, per the addon's query_scope convention).
     *
     * @param  Builder  $query
     */
    public function apply($query, $values)
    {
        $raw = $values['tour_type:filter_tour_type'] ?? null;

        if ($raw === null || $raw === '') {
            return;
        }

        $selected = is_array($raw) ? $raw : explode('|', (string) $raw);
        $selected = array_values(array_filter(
            $selected,
            fn ($value) => in_array($value, ['guided', 'self-guided'], true)
        ));

        if (empty($selected)) {
            return;
        }

        // The tag already scopes to `from="guided|self-guided"`, and Statamic's
        // EntryQueryBuilder appends to its internal collections array rather
        // than replacing it — so chaining `whereIn('collection', ...)` here
        // would not narrow the listing. Resolve the matching entry IDs up
        // front and constrain the outer query by id instead.
        $ids = Entry::query()
            ->whereIn('collection', $selected)
            ->get(['id'])
            ->map->id()
            ->all();

        $query->whereIn('id', $ids ?: [null]);
    }

    public function visibleTo($key)
    {
        return $key === 'entries';
    }
}
