<?php

namespace Tests\Unit;

use App\User;
use App\Shipment;
use Tests\TestCase;

class TrackShipmentTest extends TestCase
{

    /**
     * Check tracking (guest).
     *
     * @return void
     */
    public function testGuestTrackShipmentTest()
    {
        $this->post('/track', ['tracking_number' => 10008945591])
            ->assertOk()
            ->assertSeeText('Delivered')
            ->assertSeeText('484340955945');

    }


    /**
     * Check tracking (authenticated user).
     *
     * @return void
     */
    public function testAuthenticatedTrackShipmentTest()
    {
        // Daryl's Account
        $user = User::find(195);

        // Last fedex shipment delivered
        $shipment = Shipment::where('status_id', 6)->where('carrier_id', 2)->orderBy('id', 'desc')->first();

        $response = $this->actingAs($user)->post('/track', ['tracking_number' => $shipment->consignment_number])
            ->assertRedirect('shipments/' . $shipment->id);

        $this->followRedirects($response)
            ->assertOk()
            ->assertSeeText('Delivered')
            ->assertSeeText($shipment->carrier_consignment_number);

    }


}