<?php

namespace Database\Factories;

use App\Models\Hour;
use App\Models\HourType;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\TechnicalReport;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hour>
 */
class HourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function definition(): array
    {

        $start = fake()->dateTimeThisYear;
        $end = fake()->dateTimeInInterval($start,"+2 days");

        return [
            'start' => $start,
            'end' => $end,
            'hour_type_id' => HourType::all()->random()['id'],
            'user_id' => User::all()->random()->id,
        ];
    }
}
