<?php

namespace App\Livewire\Concerns;

use Statamic\Tags\Collection\Entries;

trait HandlesQueryScopeCounts
{
    protected function updateCountsWithBatchQuery($baseParams, $fieldHandle)
    {
        // Initialize counts for configured options
        $this->statamic_field['counts'] = array_fill_keys(array_keys($this->statamic_field['options']), 0);

        // Ensure the current scope modifier is present in query_scope
        $params = $baseParams;
        $scopes = [];
        if (isset($params['query_scope']) && is_string($params['query_scope'])) {
            $scopes = array_filter(explode('|', $params['query_scope']));
        }
        if (! in_array($this->modifier, $scopes, true)) {
            $scopes[] = $this->modifier;
        }
        $params['query_scope'] = implode('|', $scopes);

        // For each option, run a small entries query with that option applied via query_scope
        foreach (array_keys($this->statamic_field['options']) as $optionKey) {
            $optionParams = $params;
            $optionParams[$this->getParamKey()] = $optionKey; // e.g. "walking_grade:filter_grade" => "moderate"

            $entries = (new Entries($this->generateParamsForCount($this->collection, $optionParams)))->get();
            $this->statamic_field['counts'][$optionKey] = method_exists($entries, 'count') ? $entries->count() : 0;
        }
    }
}
