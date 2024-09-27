<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Business or artistic projects',
                'slug' => 'business-or-artistic-projects',
                'description' => 'Business or artistic projects',
                'image' => 'images/categories/project.png',
                'parent_id' => null
            ],
            [
                'name' => 'Associative fund-raising and other assistance',
                'slug' => 'associative-fund-raising-and-other-assistance',
                'description' => 'Associative fund-raising and other assistance',
                'image' => 'images/categories/collecte-associative.png',
                'parent_id' => null
            ],
            [
                'name' => 'Charity, social or humanitarian project',
                'slug' => 'charity-social-or-humanitarian-project',
                'description' => 'Charity, social or humanitarian project',
                'image' => 'images/categories/support-frame.png',
                'parent_id' => null
            ],
            [
                'name' => 'Birthdays or other celebrations',
                'slug' => 'birthdays-or-other-celebrations',
                'description' => 'Birthdays or other celebrations',
                'image' => 'images/categories/anniversary.png',
                'parent_id' => null
            ]
        ];

        foreach ($categories as $category) {
            $slug = $category['slug'];
            unset($category['slug']);

            Category::updateOrCreate([
                'slug' => $slug
            ], $category);
        }
    }
}
