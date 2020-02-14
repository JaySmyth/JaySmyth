<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;

class ShipmentHistoryTest extends TestCase
{
    /**
     * Check shipment history page.
     *
     * @return void
     */
    public function testShipmentHistoryTest()
    {
        // Courier Demo Account
        $user = User::find(2012);

        $this->actingAs($user)->get('/shipments')
            ->assertOk()
            ->assertSeeText('shipment history')
            ->assertSeeText($user->name);
    }
}
