<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => fake()->randomElement([Plan::TYPE_BASIC, Plan::TYPE_PREMIUM]),
            'from' => fake()->dateTimeBetween('-5 years', 'now'),
            'to'   => fake()->dateTimeBetween('now', '5 years'),
        ];
    }
}
