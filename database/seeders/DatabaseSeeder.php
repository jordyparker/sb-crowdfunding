<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info("Seeding Tables 🌱");
        $this->call(CategorySeeder::class);
        User::factory(20)->create();
        $this->call(DonationSeeder::class);
        $this->command->info("Seeding Completed! 🤩");
    }
}
