<?php

namespace Database\Factories;

use App\Models\DonationCampaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = fake()->unique()->safeEmail();

        $phoneNumber = '6' . [
            '50', '51', '52', '53',
            '54', '55', '56', '57',
            '58', '59', '80', '81',
            '82', '83', '84', '70',
            '71', '72', '73', '74',
            '75', '76', '77', '91',
            '92', '93', '94', '95',
            '96', '97', '98', '99'
        ][random_int(0, 27)] . random_int(100000, 999999);

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => explode('@', $email)[0],
            'email' => $email,
            'phone' => $phoneNumber,
            'dail_code' => '+237',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'account_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

     /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $campaings = random_int(1, 5);
            for ($i = 0; $i < $campaings; $i++) {
                $name = fake()->words(6, true);
                $minAmount = random_int(500, 5000);
                $participants = random_int(10, 200);

                DonationCampaign::query()
                    ->create([
                        'creator_id' => $user->id,
                        'creator_type' => get_class($user),
                        'receiver_id' => $user->id,
                        'receiver_type' => get_class($user),
                        'slug' => generateSlug(new DonationCampaign, $name),
                        'name' => $name,
                        'description' => fake()->sentence(150),
                        'number_of_participants' => $participants,
                        'min_donation_amount' => $minAmount,
                        'target_amount' => $minAmount * $participants,
                        'currency' => 'XAF',
                        'category_id' => fake()->boolean() ? random_int(1, 4) : null,
                        'ends_at' => fake()->boolean() ?
                            now()->addDays(random_int(1, 10)) : null
                    ]);
            }
        });
    }
}
