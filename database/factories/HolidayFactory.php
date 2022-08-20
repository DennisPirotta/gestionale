<?php

namespace Database\Factories;

use App\Models\Holiday;
use App\Models\Hour;
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
        $start = fake()->dateTimeThisYear->setTime(0,0,0);
        $end = fake()->dateTimeInInterval($start,'+10 days')->setTime(0,0,0);
        $user = User::all()->random()->id;

        $hour = Hour::create([
            'start' => $start,
            'end' => $end,
            'user_id' => $user,
            'hour_type_id' => 6
        ]);

        return [
            'approved' => fake()->boolean,
            'user_id' => $user,
            'allDay' => fake()->boolean,
            'hour_id' => $hour->id
        ];
    }
}
