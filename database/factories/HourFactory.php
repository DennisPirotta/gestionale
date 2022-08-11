<?php

namespace Database\Factories;

use App\Models\Holiday;
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
        $order = 0;
        $holiday = 0;
        $report = 0;

        switch (random_int(0,2)){
            case 0: {
                $order = Order::all()->random()->id;
                $holiday = null;
                $report = null;
                break;
            }
            case 1: {
                $order = null;
                $holiday = Holiday::all()->random()->id;
                $report = null;
                break;
            }
            case 2: {
                $order = null;
                $holiday = null;
                $report = TechnicalReport::all()->random()->id;
                break;
            }
        }

        $start = fake()->dateTimeThisYear;

        return [
            'start' => $start,
            'end' => fake()->dateTimeInInterval($start,"+10 days"),
            'order' => $order,
            'report' => $report,
            'holiday' => $holiday
        ];
    }
}
