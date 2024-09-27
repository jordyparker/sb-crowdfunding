<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class ApiRequestStructureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a request without api key returns forbidden response
     */
    public function test_request_without_api_key_returns_forbidden_response(): void
    {
        $this->post(route('api.login'))
            ->assertForbidden();
    }

    /**
     * Test a request with invalid api key returns forbidden response
     */
    public function test_request_with_invalid_api_key_returns_forbidden_response(): void
    {
        $this->withHeaders([
            'x-api-key' => str()->random(30)
        ])
            ->post(route('api.login'))
            ->assertForbidden();
    }

    /**
     * Test a request with valid api key returns successful response
     */
    public function test_request_with_valid_api_key_returns_successful_response(): void
    {
        $this->withHeaders([
            'x-api-key' => config('app.api_key')
        ])
            ->get(route('api.categories.index'))
            ->assertOk();
    }

    /**
     * Test request without token requiring authentication returns route not found exception
     */
    public function test_request_without_token_requiring_authentication_returns_route_not_found_exception(): void
    {
        Exceptions::fake();

        $this->withHeaders([
            'x-api-key' => config('app.api_key')
        ])
            ->post(route('api.logout'));

        Exceptions::assertReported(RouteNotFoundException::class);
    }
}
