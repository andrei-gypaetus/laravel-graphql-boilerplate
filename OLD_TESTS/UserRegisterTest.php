<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/graphql');
        $response->assertStatus(200);
    }
}
