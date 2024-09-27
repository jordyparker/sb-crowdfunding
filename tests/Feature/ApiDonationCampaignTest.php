<?php

namespace Tests\Feature;

use App\Enums\PaymentMethod;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Exceptions;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class ApiDonationCampaignTest extends TestCase
{
    Use RefreshDatabase;

    /**
     * Test create donation campaign without authorization token returns route not found exception
     */
    public function test_create_donation_campaign_without_authorization_token_returns_route_not_found_exception(): void
    {
        Exceptions::fake();

        $this->withHeaders([
            'x-api-key' => config('app.api_key')
        ])
            ->post(route('api.donation_campaigns.store'));

        Exceptions::assertReported(RouteNotFoundException::class);
    }

    /**
     * Test create donation campaign with min donation amount > target amount returns 422 response
     */
    public function test_create_donation_campaign_with_min_donation_amount_gt_target_amount_returns_422_response(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('ACCESS TOKEN');

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ])
            ->post(route('api.donation_campaigns.store'), [
                'name' => fake()->words(3, true),
                'description' => substr(fake()->sentence(250), 0, 255),
                'target_amount' => 50000,
                'min_donation_amount' => 60000,
                'currency' => 'XAF'
            ])
            ->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'errors' => [
                    'min_donation_amount' => ['The min donation amount field must be less than 50000.']
                ]
            ]);
    }

    /**
     * Test create donation campaign with non exiating category returns 422 response
     */
    public function test_create_donation_campaign_with_non_existing_category_returns_422_response(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('ACCESS TOKEN');

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ])
            ->post(route('api.donation_campaigns.store'), [
                'name' => fake()->words(3, true),
                'description' => substr(fake()->sentence(250), 0, 255),
                'target_amount' => 60000,
                'min_donation_amount' => 500,
                'category_id' => 0,
                'currency' => 'XAF'
            ])
            ->assertStatus(422)
            ->assertJson([
                'code' => 422,
                'errors' => [
                    'category_id' => ['The selected category id is invalid.']
                ]
            ]);
    }

    /**
     * Test create donation campaign with valid payload returns 422 response
     */
    public function test_create_donation_campaign_with_valid_payload_returns_successfull_response(): void
    {
        $user = User::factory()->create();

        $token = $user->createToken('ACCESS TOKEN');

        $category = Category::factory()->create();

        $name = fake()->words(3, true);

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ])
            ->post(route('api.donation_campaigns.store'), [
                'name' => $name,
                'description' => substr(fake()->sentence(250), 0, 255),
                'target_amount' => 60000,
                'min_donation_amount' => 500,
                'category_id' => $category->id,
                'currency' => 'XAF'
            ])
            ->assertStatus(201)
            ->assertJson([
                'code' => 'CAMPAIGN_CREATED'
            ])
            ->assertJsonPath('data.category_id', $category->id)
            ->assertJsonPath('data.name', $name);
    }

    /**
     * Test donation to a close donation campaign returns 422 response
     */
    public function test_donation_to_a_close_donation_campaign_returns_422_response(): void
    {
        $user = User::factory()->create();

        $campaign = $user->campaignsOwned()->latest()->first();

        $campaign->closeCampaign();

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])
            ->post(route('api.donation_campaigns.donate', $campaign->id), [
                'amount' => 400,
                'currency' => 'XAF',
                'payment_number' => '+237670000000',
                'payment_method' => PaymentMethod::ORANGE_MONEY->value
            ])
            ->assertStatus(422)
            ->assertJson([
                'message' => sprintf('%s cannot receive donations anymore.', $campaign->getName())
            ]);
    }

    /**
     * Test donate with insufficient minimum amount returns 422 response
     */
    public function test_donate_with_insufficient_amount_returns_422_response(): void
    {
        $user = User::factory()->create();

        $campaign = $user->campaignsOwned()->latest()->first();

        $campaign->update(['min_donation_amount' => 500]);

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])
            ->post(route('api.donation_campaigns.donate', $campaign->id), [
                'amount' => 400,
                'currency' => 'XAF',
                'payment_number' => '+237670000000',
                'payment_method' => PaymentMethod::ORANGE_MONEY->value
            ])
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'amount' => [
                        (
                            sprintf('Minimum amount set for this donation is %s %s.',
                            convertAmount(
                                'XAF',
                                $campaign->getTargetCurrency(),
                                $campaign->getMinimumAmount(),
                            ),
                            'XAF'
                        ))
                    ]
                ]
            ]);
    }

    /**
     * Test campaign collected amount increased by the new donation amount
     */
    public function test_campaign_collected_amount_increased_by_the_new_donation_amount(): void
    {
        $user = User::factory()->create();

        $campaign = $user->campaignsOwned()->latest()->first();

        $campaign->update(['min_donation_amount' => 500]);

        $collectedAmount = $campaign->donations()->sum('amount') ?: 0;

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])
            ->post(route('api.donation_campaigns.donate', $campaign->id), [
                'amount' => 500,
                'currency' => 'XAF',
                'payment_number' => '+237670000000',
                'payment_method' => PaymentMethod::ORANGE_MONEY->value
            ])
            ->assertStatus(201);

        $this->assertTrue($campaign->donations()->sum('amount') == $collectedAmount + 500);
    }

    /**
     * Test donation campaign is closed when target amount is reached
     */
    public function test_donation_campaign_is_closed_when_target_amount_is_reached(): void
    {
        $user = User::factory()->create();

        $campaign = $user->campaignsOwned()->latest()->first();

        $campaign->update([
            'min_donation_amount' => 500,
            'target_amount' => 5000
        ]);

        $this->withHeaders([
            'x-api-key' => config('app.api_key'),
        ])
            ->post(route('api.donation_campaigns.donate', $campaign->id), [
                'amount' => 5000,
                'currency' => 'XAF',
                'payment_number' => '+237670000000',
                'payment_method' => PaymentMethod::ORANGE_MONEY->value
            ])
            ->assertStatus(201);

        $campaign->refresh();

        $this->assertTrue($campaign->isClosed() === true);
    }
}
