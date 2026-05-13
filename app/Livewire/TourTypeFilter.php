<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Reach\StatamicLivewireFilters\Http\Livewire\LfCheckboxFilter;

/**
 * Tour-type filter for /tours.
 *
 * "Tour type" maps to the entry's collection (`guided` or `self-guided`),
 * which isn't a real blueprint field — so we skip the blueprint mount and
 * supply the two options statically. Filtering is handled by the
 * `App\Scopes\TourType` query scope.
 */
class TourTypeFilter extends LfCheckboxFilter
{
    public function mountIsLivewireFilter($blueprint = null): void
    {
        // No-op: there's no entry data backing this filter, so there's no
        // blueprint field to resolve.
        $this->statamic_field = ['type' => 'select'];
    }

    #[Computed(persist: true)]
    public function filterOptions(): array
    {
        return [
            'guided' => __('Guided'),
            'self-guided' => __('Self-guided'),
        ];
    }
}
