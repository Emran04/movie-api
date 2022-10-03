<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'        => fake()->text(),
            'poster'       => fake()->imageUrl(),
            'release_year' => fake()->year(),
            'rent_from'    => fake()->dateTimeBetween('-5 years', 'now'),
            'rent_to'      => fake()->dateTimeBetween('now', '5 years'),
            'rent_price'   => fake()->randomFloat(8, 1, 200),
            'plan'         => fake()->randomElement([Movie::PLAN_BASIC, Movie::PLAN_PREMIUM]),
        ];
    }
}
