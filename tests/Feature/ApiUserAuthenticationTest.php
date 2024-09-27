<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApiUserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a login request with invalid credentials returns 422 response
     */
    public function test_login_request_with_invalid_credentials_returns_422_responsee(): void
    {
        $this
            ->withHeaders([
                'x-api-key' => config('app.api_key')
            ])
            ->post(route('api.login'), [
                'username_type' => 'email',
                'username' => 'jondoe@gmail.com',
                'password' => 'password'
            ])
            ->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'message' => __('auth.failed'),
                'errors' => [
                    'email' => [__('auth.failed')]
                ]
            ]);
    }

    /**
     * Test a login request with valid credentials returns successful response
     */
    public function test_login_request_with_valid_credentials_returns_successful_responsee(): void
    {
        $user = User::factory()->create();

        $this
            ->withHeaders([
                'x-api-key' => config('app.api_key')
            ])
            ->post(route('api.login'), [
                'username_type' => 'email',
                'username' => $user->email,
                'password' => 'password'
            ])
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('auth_token')
                    ->has('user')
                    ->where('code', 'LOGIN_SUCCESS')
                    ->where('token_type', 'Bearer')
                    ->where('status', true)
                    ->etc()
            )
            ->assertJsonPath('user.email', $user->email);
    }

    /**
     * Test a signup request with existing email returns 422 response
     */
    public function test_signup_request_with_existing_email_returns_422_responsee(): void
    {
        $user = User::factory()->create();

        $this
            ->withHeaders([
                'x-api-key' => config('app.api_key')
            ])
            ->post(route('api.register'), [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => $user->email,
                'dail_code' => $user->dail_code,
                'phone' => fake()->phoneNumber(),
                'password' => 'password',
                'confirmation_password' => 'password'
            ])
            ->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'errors' => [
                    'email' => ['The email has already been taken.']
                ]
            ]);
    }

    /**
     * Test a signup request with existing phone returns 422 response
     */
    public function test_signup_request_with_existing_phone_returns_422_responsee(): void
    {
        $user = User::factory()->create();

        $this
            ->withHeaders([
                'x-api-key' => config('app.api_key')
            ])
            ->post(route('api.register'), [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->email(),
                'dail_code' => $user->dail_code,
                'phone' => $user->phone,
                'password' => 'password',
                'password_confirmation' => 'password'
            ])
            ->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'errors' => [
                    'phone' => ['The phone has already been taken.']
                ]
            ]);
    }

    /**
     * Test a signup request with non existing credentials returns 201 response
     */
    public function test_signup_request_with_non_existing_credentials_returns_201_responsee(): void
    {
        $email = fake()->email();

        $this
            ->withHeaders([
                'x-api-key' => config('app.api_key')
            ])
            ->post(route('api.register'), [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => $email,
                'dail_code' => '+237',
                'phone' => '670090045',
                'password' => 'password',
                'password_confirmation' => 'password'
            ])
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('user')
                    ->where('code', 'REGISTER_SUCCESS')
                    ->etc()
            )
            ->assertJsonPath('user.email', $email);
    }
}
