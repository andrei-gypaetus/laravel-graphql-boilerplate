<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\User;
use Tests\TestCase;
use Joselfonseca\LighthouseGraphQLPassport\Events\UserLoggedOut;
use Illuminate\Support\Facades\Event;

class LogoutTest extends TestCase
{
  /**
   * Test if the token is being invalidated on logout
   *
   * @return void
   */
  public function test_it_invalidates_token_on_logout()
  {
    Event::fake([UserLoggedOut::class]);

    $this->artisan('migrate', ['--database' => 'testbench']);

    $username = "admin";
    $email = "admin@admin.com";
    $password = bcrypt("password");

    $user = User::factory()->create([
      'name' => $username,
      'email' => $email,
      'password' => $password
    ]);

    $this->createClientPersonal($user);
    $token = $user->createToken('test Token');
    $token = $token->accessToken;
    $response = $this->postGraphQL([
      'query' => 'mutation {
              logout {
                  status
                  message
              }
          }',
    ], [
      'Authorization' => 'Bearer ' . $token,
    ]);
    $responseBody = json_decode($response->getContent(), true);
    $this->assertArrayHasKey('logout', $responseBody['data']);
    $this->assertArrayHasKey('status', $responseBody['data']['logout']);
    $this->assertArrayHasKey('message', $responseBody['data']['logout']);
    Event::assertDispatched(UserLoggedOut::class, function (UserLoggedOut $event) use ($user) {
      return $user->id === $event->user->id;
    });
  }
}
