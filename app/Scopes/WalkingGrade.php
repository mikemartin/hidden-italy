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
        if (isset($values['walking_grade'])) {
            switch ($values['walking_grade']) {
                case 'moderate':
                    $query->whereBetween('grade', [0, 2]);
                    break;
                case 'intermediate':
                    $query->whereBetween('grade', [2, 3]);
                    break;
                case 'challenging':
                    $query->whereBetween('grade', [3, 5]);
                    break;
            }
        }
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
