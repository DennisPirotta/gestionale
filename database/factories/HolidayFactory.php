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
        $start = fake()->dateTimeThisYear->setTime(0, 0, 0);

        return [
            'start' => $start,
            'end' => fake()->dateTimeInInterval($start, '+10 days')->setTime(0, 0, 0),
            'approved' => fake()->boolean,
            'user_id' => User::all()->random()->id,
        ];
    }
}
