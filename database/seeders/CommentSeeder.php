<?php

namespace Database\Seeders;

use App\Models\Comment;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++){
            Comment::create([
                'comment' => $faker->realText,
                'user_id' => $faker->numberBetween(1,20),
                'recipe_id' => $faker->numberBetween(5,10),
            ]);
        }
    }
}
