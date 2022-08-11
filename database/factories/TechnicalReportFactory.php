<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TechnicalReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer' => Customer::all()->random()->id,
            'customer2' => Customer::all()->random()->id,
            'order' => Order::all()->random()->id,
            'number' => fake()->randomNumber(5)
        ];
    }
}
