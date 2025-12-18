<?php

namespace App\Livewire;

use App\Livewire\Concerns\HandlesQueryScopeCounts;
use Reach\StatamicLivewireFilters\Http\Livewire\LfRadioFilter as BaseLfRadioFilter;

class QueryScopeRadioFilter extends BaseLfRadioFilter
{
    use HandlesQueryScopeCounts;
}
