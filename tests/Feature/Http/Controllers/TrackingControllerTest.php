<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TrackingController
 */
class TrackingControllerTest extends TestCase
{
    // use RefreshDatabase;

    /**
    * @param None
    *
    * @return $userId, $roleName, $companyId
    */
    public function myUserProvider()
    {
        return [
            'IFS Admin User'     => ['1', 'ifsa', '1'],
            'IFS Manager' => ['2', 'ifsm', '1'],
            'IFS User'       => ['3', 'ifsu', '1'],
            'Customer Manager' => ['2', 'cusa', '1'],
            'Customer User'       => ['3', 'cust', '1'],
        ];
    }

    /**
    * @test
     */
    public function a_non_authenticated_user_can_track_a_shipment()
    {
        $this->post('/track', ['tracking_number' => 10008945591])
            ->assertOk()
            ->assertSeeText('Delivered')
            ->assertSeeText('484340955945');
    }

    /**
    * @test
    * @dataProvider myUserProvider
     */
    public function an_authenticated_user_can_track_a_shipment($userId, $roleName, $companyId)
    {
        $user = buildTestUser($userId, $roleName, $companyId);

        $response = $this->actingAs($user)->post('/track', ['tracking_number' => '10008945591'])
            ->assertOk()
            ->assertSeeText('Delivered')
            ->assertSeeText('484340955945');
    }
}
