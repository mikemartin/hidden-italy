<?php

namespace App\Livewire;

use App\Livewire\Concerns\HandlesQueryScopeCounts;
use Reach\StatamicLivewireFilters\Http\Livewire\LfCheckboxFilter as BaseLfCheckboxFilter;

class QueryScopeCheckboxFilter extends BaseLfCheckboxFilter
{
    use HandlesQueryScopeCounts;
}
