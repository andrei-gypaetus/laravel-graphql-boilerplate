<?php

namespace Tests\Feature\GraphQL\Mutations;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Joselfonseca\LighthouseGraphQLPassport\Events\ForgotPasswordRequested;

class ForgotPasswordTest extends TestCase
{
  /**
   * Test if the token is being invalidated on logout
   *
   * @return void
   */
  public function test_it_sends_recover_password_email()
  {
    Mail::fake();
    Notification::fake();
    Event::fake([ForgotPasswordRequested::class]);
    $this->createClient();

    $username = "admin";
    $email = "admin@admin.com";
    $password = bcrypt("password");

    $user = User::factory()->create([
      'name' => $username,
      'email' => $email,
      'password' => $password
    ]);

    $response = $this->postGraphQL([
      'query' => 'mutation {
                forgotPassword(input: {
                    email: "admin@admin.com"
                }) {
                    status
                    message
                }
            }',
    ]);
    $responseBody = json_decode($response->getContent(), true);
    $this->assertArrayHasKey('forgotPassword', $responseBody['data']);
    $this->assertArrayHasKey('status', $responseBody['data']['forgotPassword']);
    $this->assertArrayHasKey('message', $responseBody['data']['forgotPassword']);
    $this->assertEquals('EMAIL_SENT', $responseBody['data']['forgotPassword']['status']);
    Notification::assertSentTo($user, ResetPassword::class);
    Event::assertDispatched(ForgotPasswordRequested::class, function (ForgotPasswordRequested $event) use ($user) {
      return $user->email === $event->email;
    });
  }

  /**
   * Test if it throws when the email is not sent
   *
   * @return void
   */
  public function test_it_throws_exception_if_email_not_sent()
  {
    Mail::fake();
    Notification::fake();
    Event::fake([ForgotPasswordRequested::class]);

    $this->createClient();

    $response = $this->postGraphQL([
      'query' => 'mutation {
                forgotPassword(input: {
                    email: "nonemail@example.com"
                }) {
                    status
                    message
                }
            }',
    ]);

    $responseBody = json_decode($response->getContent(), true);
    $this->assertArrayHasKey('errors', $responseBody);
    $this->assertArrayHasKey('message', $responseBody['errors'][0]);
    $this->assertArrayHasKey('extensions', $responseBody['errors'][0]);
    $this->assertEquals('Email not sent', $responseBody['errors'][0]['message']);
    $this->assertEquals('passwords.user', $responseBody['errors'][0]['extensions']['reason']);

    Notification::assertNothingSent();
    Event::assertNotDispatched(ForgotPasswordRequested::class);
  }
}
