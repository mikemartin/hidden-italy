<?php

namespace App\Livewire;

use Reach\StatamicLivewireFilters\Http\Livewire\LfCheckboxFilter;
use Livewire\Attributes\Computed;

class SeasonsCheckboxFilter extends LfCheckboxFilter
{
    /**
     * Override filter options to append Italy-specific month ranges
     * for the seasons taxonomy without affecting other filters.
     */
    #[Computed(persist: true)]
    public function filterOptions(): array
    {
        $options = parent::filterOptions();

        if (($this->field ?? null) === 'seasons' && is_array($options)) {
            $seasonMonths = [
                'spring' => 'Mar - May',
                'summer' => 'Jun - Aug',
                'autumn' => 'Sep - Nov',
                'winter' => 'Dec - Feb',
            ];

            foreach ($options as $value => $label) {
                if (isset($seasonMonths[$value])) {
                    $options[$value] = sprintf('%s (%s)', $label, $seasonMonths[$value]);
                }
            }
        }

        return $options;
    }
}
