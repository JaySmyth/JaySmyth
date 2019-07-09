<?php

namespace Tests\Feature;

use Tests\TestCase;

class RequireAuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRequireAuthTest()
    {
        $this->get('/')->assertStatus(302);
        $this->get('/shipments')->assertStatus(302);
        $this->get('/companies')->assertStatus(302);
        $this->get('/users')->assertStatus(302);
        $this->get('/account')->assertStatus(302);
    }

}