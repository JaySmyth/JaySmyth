<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ShipmentsController
 */
class ShipmentsControllerTest extends TestCase
{
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
     * Check shipment history page.
     *
     * @test
     * @dataProvider myUserProvider
     */
    public function an_authenticated_user_can_see_shipment_history($userId, $roleName, $companyId)
    {
        $user = buildTestUser($userId, $roleName, $companyId);

        $this->actingAs($user)->get('/shipments')
            ->assertOk()
            ->assertSeeText('shipment history')
            ->assertSeeText($user->name);
    }
}
