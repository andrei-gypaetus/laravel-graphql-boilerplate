<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function testProfileQuery()
    {
        $user = User::factory()->create([
            'name'=>"Admin",
            'email'=>"admin@admin.com",
        ]);
        
        Passport::actingAs($user);

        $this->graphQL(
            /** @lang GraphQL */
            '
            {
                profile {
                    id
                    email
                }
            }
            '
        )
            ->assertJson([
                'data' => [
                    'profile' => [
                            'id' => 1,
                            'email' => "admin@admin.com",
                    ],
                ],
            ]);
    }
    
    public function testInvalidProfileQuery()
    {
        $this->graphQL(/** @lang GraphQL */ '
            {
                profile {
                    id
                    email
                }
            }
            ')->assertJson([
                'data' => [
                    'profile' => null
                ],
            ]);
    }
}
