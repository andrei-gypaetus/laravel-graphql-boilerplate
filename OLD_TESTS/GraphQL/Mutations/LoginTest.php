<?php

namespace Tests\Feature\GraphQL\Mutations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {

        $this->createClient();

        $username = "admin3";
        $email = "admin3@admin.com";
        $rawPassword="password";
        $password = bcrypt($rawPassword);

        $user = User::factory()->create([
            'name' => $username,
            'email' => $email,
            'password' => $password
        ]);

        // Passport::actingAsClient($user);

        dd($this->graphQL(
            /** @lang GraphQL */
            '
            mutation ($input: LoginInput){
                login(input: $input) {
                    user {
                        id
                        email
                    }
                }
            }
            ', [
                "input" => [ "username" => $username, "password" => "password" ]
            ]
            ));
            // ->assertJson([
            //     'data' => [
            //         'login' => [
            //             "user" => [
            //                 'id' => 1,
            //                 'email' => $email,
            //             ]
            //         ],
            //     ],
            // ]);
    }
}