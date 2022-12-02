<?php

namespace Database\Factories;

use App\Models\HourType;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hour>
 */
class HourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function definition(): array
    {
        return [
            'count' => fake()->numberBetween('0', '10'),
            'hour_type_id' => HourType::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'date' => fake()->dateTimeThisYear->format('Y-m-d'),
        ];
    }
}
