<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\User;
use Tests\TestCase;
use Joselfonseca\LighthouseGraphQLPassport\Events\UserLoggedIn;
use Illuminate\Support\Facades\Event;

class LoginTest extends TestCase
{
  /**
   * Test if the logged in user gets the access tokens.
   *
   * @return void
   */
  public function test_it_gets_access_token()
  {
    Event::fake([UserLoggedIn::class]);

    $this->createClient();

    $username = "admin";
    $email = "admin@admin.com";
    $rawPassword = "password";
    $password = bcrypt("password");

    $user = User::factory()->create([
      'name' => $username,
      'email' => $email,
      'password' => $password
    ]);

    $response = $this->graphQL(/* @lang GraphQL */
      '
      mutation Login($input: LoginInput) {
          login(input: $input) {
              access_token
              refresh_token
              user {
                  id
                  name
                  email
              }
          }
      }
  ',
      [
        'input' => ["username" => $email, "password" => $rawPassword],
      ]
    );

    $response->assertJsonStructure([
      'data' => [
        'login' => [
          'access_token',
          'refresh_token',
          'user' => [
            'id',
            'name',
            'email',
          ],
        ],
      ],
    ]);

    Event::assertDispatched(UserLoggedIn::class, function (UserLoggedIn $event) use ($user) {
      return $event->user->id === $user->id;
    });
  }

  /**
   * Test if it returns error for invalid credentials.
   *
   * @return void
   */
  public function test_it_returns_error_for_invalid_credentials()
  {
    Event::fake([UserLoggedIn::class]);

    $this->createClient();

    $email = "someUnregistredEmail@nobody.com";
    $rawPassword = "someRawPassword";

    $response = $this->graphQL(/* @lang GraphQL */
      '
            mutation Login($input: LoginInput) {
                login(input: $input) {
                    access_token
                    refresh_token
                    user {
                        id
                        name
                        email
                    }
                }
            }
        ',
      [
        'input' => [
          'username' => $email,
          'password' => $rawPassword,
        ],
      ]
    );

    $response->assertJsonStructure([
      'errors' => [
        [
          'message',
          'extensions' => [
            'category',
            'reason',
          ],
        ],
      ],
    ]);

    $decodedResponse = json_decode($response->getContent(), 'true');

    $this->assertEquals('The user credentials were incorrect.', $decodedResponse['errors'][0]['extensions']['reason']);

    Event::assertNotDispatched(UserLoggedIn::class);
  }
}
