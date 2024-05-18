<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Testing\Fakes\Fake;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            "title" => fake()->text(15),
            "body" => fake()->text(1500),
            "created_at" => fake()->dateTimeBetween(),
        ];
    }
}
