<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\TechnicalReport;
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
     * @throws Exception
     */
    public function definition(): array
    {
        $order = null;
        $holiday = null;
        $report = null;
        $description = null;

        switch (random_int(0,2)){
            case 0: {
                $order = Order::all()->random()->id;
                break;
            }
            case 1: {
                $report = TechnicalReport::all()->random()->id;
                break;
            }
            case 2: {
                $description = fake()->text(20);
                break;
            }
        }

        $start = fake()->dateTimeThisYear;

        return [
            'start' => $start,
            'end' => fake()->dateTimeInInterval($start,"+10 days"),
            'order' => $order,
            'report' => $report,
            'holiday' => $holiday,
            'description' => $description
        ];
    }
}
