<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            "title" => fake()->text(15),
            "body" => fake()->text(150),
        ];
    }
}
