<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Faker\Factory;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++){
            Recipe::create([
                'title' => $faker->words(3, true),
                'description' => $faker->text,
                'ingredients' => $faker->text,
                'instructions' => $faker->text,
                'user_id' => $faker->numberBetween(1,20),
            ]);
        }
    }
}
