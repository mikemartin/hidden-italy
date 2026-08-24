<?php

namespace Tests\Feature;

use Tests\TestCase;

class TourListingOrderTest extends TestCase
{
    public function test_combined_listing_puts_australasian_tours_last(): void
    {
        $this->get('/tours')
            ->assertOk()
            ->assertSeeInOrder(['Verona and the Dolomites', 'Bondi to Manly', 'Waiheke']);
    }

    public function test_self_guided_listing_puts_australasian_tours_last(): void
    {
        $this->get('/tours/self-guided')
            ->assertOk()
            ->assertSeeInOrder(['Umbria Classic', 'Bondi to Manly']);
    }

    public function test_guided_listing_puts_australasian_tours_last(): void
    {
        $this->get('/tours/guided')
            ->assertOk()
            ->assertSeeInOrder(['Verona and the Dolomites', 'Waiheke']);
    }
}
