<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'category_id' => Category::inRandomOrder()->first()->id,
            'user_id' => User::factory(),
            'image' => 'https://picsum.photos/seed/'.$this->faker->uuid.'/640/480',
            'published_at' => fake()->dateTime(),
            'slug' => Str::slug(fake()->title()).'-'.$this->faker->unique()->numberBetween(1, 1000),
            'status' => 'published',
        ];
    }
}

