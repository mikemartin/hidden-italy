<?php

namespace App\Livewire;

use App\Livewire\Concerns\HandlesQueryScopeCounts;
use Reach\StatamicLivewireFilters\Http\Livewire\LfSelectFilter as BaseLfSelectFilter;

class QueryScopeSelectFilter extends BaseLfSelectFilter
{
    use HandlesQueryScopeCounts;
}
