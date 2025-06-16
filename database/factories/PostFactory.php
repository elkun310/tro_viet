<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id'      => User::inRandomOrder()->value('id') ?? User::factory(),
            'code'         => strtoupper(Str::random(8)),
            'category_id'  => Category::inRandomOrder()->value('id') ?? null,
            'title'        => $this->faker->sentence(6),
            'description'  => $this->faker->paragraph(3),
            'price'        => $this->faker->numberBetween(500, 5000), // theo nghìn VND
            'area'         => $this->faker->randomFloat(1, 10, 60),
            'address'      => $this->faker->address,
            'ward_id'      => Ward::inRandomOrder()->value('id') ?? null,
            'is_featured'  => $this->faker->boolean(20),
            'status'       => $this->faker->randomElement(['active', 'pending', 'expired']),
        ];
    }
}
