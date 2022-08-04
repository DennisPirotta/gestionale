<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Holiday>
 */
class HolidayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = fake()->dateTimeThisYear;
        $end = fake()->dateTimeInInterval($start,"+5 days");
        return [
            'start' => $start,
            'end' => $end,
            'approved' => fake()->boolean,
            'user' => User::all()->random()->id
        ];
    }
}
