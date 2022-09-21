<?php

namespace Database\Factories;

use App\Models\Hour;
use App\Models\HourType;
use App\Models\JobType;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetails>
 */
class OrderDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = fake()->dateTimeThisYear;
        $end = fake()->dateTimeInInterval($start,"+2 days");

        $hour = Hour::create([
            'start' => $start,
            'end' => $end,
            'hour_type_id' => 1,
            'user_id' => User::all()->random()->id,
        ]);

        return [
            'order_id' => Order::all()->random()->id,
            'hour_id' => $hour->id,
            'job_type_id' => JobType::all()->random()->id
        ];
    }
}
