<?php

namespace Tests\Unit\Modifiers;

use App\Modifiers\UpcomingDates;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class UpcomingDatesTest extends TestCase
{
    private UpcomingDates $modifier;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow('2026-05-20 09:00:00');
        $this->modifier = new UpcomingDates;
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_filters_out_past_dates(): void
    {
        $result = $this->modifier->index([
            '2026-05-19',
            '2026-05-21',
            '2025-01-01',
        ]);

        $this->assertCount(1, $result);
        $this->assertSame('2026-05-21', $result[0]->format('Y-m-d'));
    }

    public function test_keeps_today_as_upcoming(): void
    {
        $result = $this->modifier->index(['2026-05-20']);

        $this->assertCount(1, $result);
        $this->assertSame('2026-05-20', $result[0]->format('Y-m-d'));
    }

    public function test_sorts_upcoming_dates_ascending(): void
    {
        $result = $this->modifier->index([
            '2027-04-29',
            '2026-09-02',
            '2026-05-21',
        ]);

        $this->assertSame(
            ['2026-05-21', '2026-09-02', '2027-04-29'],
            array_map(fn ($d) => $d->format('Y-m-d'), $result),
        );
    }

    public function test_accepts_carbon_instances(): void
    {
        $result = $this->modifier->index([
            Carbon::parse('2026-05-21'),
            Carbon::parse('2026-05-19'),
        ]);

        $this->assertCount(1, $result);
        $this->assertSame('2026-05-21', $result[0]->format('Y-m-d'));
    }

    public function test_returns_empty_array_for_null(): void
    {
        $this->assertSame([], $this->modifier->index(null));
    }

    public function test_returns_empty_array_for_non_iterable(): void
    {
        $this->assertSame([], $this->modifier->index('not-an-array'));
    }

    public function test_returns_empty_array_when_all_dates_are_past(): void
    {
        $this->assertSame([], $this->modifier->index([
            '2025-01-01',
            '2026-05-19',
        ]));
    }

    public function test_unwraps_grid_rows_with_date_key(): void
    {
        $result = $this->modifier->index([
            ['date' => '2025-01-01'],
            ['date' => '2027-04-30'],
            ['date' => '2027-05-14'],
        ]);

        $this->assertSame(
            ['2027-04-30', '2027-05-14'],
            array_map(fn ($d) => $d->format('Y-m-d'), $result),
        );
    }

    public function test_unwraps_array_access_grid_rows(): void
    {
        $row = new class implements \ArrayAccess
        {
            public function offsetExists($offset): bool
            {
                return $offset === 'date';
            }

            public function offsetGet($offset): mixed
            {
                return $offset === 'date' ? '2027-04-30' : null;
            }

            public function offsetSet($offset, $value): void {}

            public function offsetUnset($offset): void {}
        };

        $result = $this->modifier->index([$row]);

        $this->assertCount(1, $result);
        $this->assertSame('2027-04-30', $result[0]->format('Y-m-d'));
    }

    public function test_skips_grid_rows_with_blank_date(): void
    {
        $result = $this->modifier->index([
            ['date' => ''],
            ['date' => null],
            ['date' => '2027-04-30'],
            [],
        ]);

        $this->assertCount(1, $result);
        $this->assertSame('2027-04-30', $result[0]->format('Y-m-d'));
    }

    public function test_reindexes_keys_starting_from_zero(): void
    {
        $result = $this->modifier->index([
            '2025-01-01',
            '2026-06-01',
            '2026-07-01',
        ]);

        $this->assertSame([0, 1], array_keys($result));
    }
}
