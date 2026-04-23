<?php

namespace App\Livewire\Concerns;

use Statamic\Tags\Collection\Entries;
use Statamic\Tags\Context;
use Statamic\Tags\Parameters;

trait HandlesQueryScopeCounts
{
    protected function updateCountsWithBatchQuery($baseParams, $fieldHandle)
    {
        $this->statamic_field['counts'] = array_fill_keys(array_keys($this->statamic_field['options']), 0);

        $params = $baseParams;
        $scopes = [];
        if (isset($params['query_scope']) && is_string($params['query_scope'])) {
            $scopes = array_filter(explode('|', $params['query_scope']));
        }
        if (! in_array($this->modifier, $scopes, true)) {
            $scopes[] = $this->modifier;
        }
        $params['query_scope'] = implode('|', $scopes);

        foreach (array_keys($this->statamic_field['options']) as $optionKey) {
            $optionParams = array_merge(
                ['from' => $this->collection],
                $params,
                [$this->getParamKey() => $optionKey],
            );

            $entries = (new Entries(Parameters::make($optionParams, Context::make([]))))->get();
            $this->statamic_field['counts'][$optionKey] = method_exists($entries, 'count') ? $entries->count() : 0;
        }
    }
}
