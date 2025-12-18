<?php

namespace App\Scopes;

use Statamic\Query\Scopes\Filter;

class WalkingGrade extends Filter
{
    /**
     * Pin the filter.
     *
     * @var bool
     */
    public $pinned = true;

    /**
     * Define the filter's title.
     *
     * @return string
     */
    public static function title()
    {
        return __('Walking Grade');
    }

    /**
     * Define the filter's field items.
     *
     * @return array
     */
    public function fieldItems()
    {
        return [
            'walking_grade' => [
                'placeholder' => __('Select Walking Grade'),
                'type' => 'radio',
                'options' => [
                    'moderate' => 'Moderate (0-2)',
                    'intermediate' => 'Intermediate (2-3)',
                    'challenging' => 'Challenging (3-5)',
                ],
            ]
        ];
    }

    /**
     * Apply the filter.
     *
     * @param \Statamic\Query\Builder $query
     * @param array $values
     * @return void
     */
    public function apply($query, $values)
    {
        // Support CP filter values and Livewire query_scope params, including multi-select via pipes.
        $raw = $values['walking_grade']
            ?? ($values['walking_grade:walking_grade'] ?? null)
            ?? ($values['walking_grade:grade'] ?? null)
            ?? ($values['walking_grade:filter_grade'] ?? null);

        if ($raw === null || $raw === '') {
            return;
        }

        $selected = is_array($raw) ? $raw : explode('|', (string) $raw);
        $selected = array_filter($selected);

        if (empty($selected)) {
            return;
        }

        // Combine multiple selected ranges using OR logic in a nested where.
        $query->where(function ($q) use ($selected) {
            $first = true;
            foreach ($selected as $option) {
                $method = $first ? 'whereBetween' : 'orWhereBetween';
                $first = false;
                switch ($option) {
                    case 'moderate':
                        $q->$method('grade', [0, 2]);
                        break;
                    case 'intermediate':
                        $q->$method('grade', [2, 3]);
                        break;
                    case 'challenging':
                        $q->$method('grade', [3, 5]);
                        break;
                }
            }
        });
    }

    /**
     * Define the applied filter's badge text.
     *
     * @param array $values
     * @return string
     */
    public function badge($values)
    {
        return match ($values['walking_grade'] ?? '') {
            'moderate' => 'Moderate (0-2)',
            'intermediate' => 'Intermediate (2-3)',
            'challenging' => 'Challenging (3-5)',
            default => '',
        };
    }

    /**
     * Determine when the filter is shown.
     *
     * @param string $key
     * @return bool
     */
    public function visibleTo($key)
    {
        return $key === 'entries' && $this->context['collection'] == 'tours';

    }
}
