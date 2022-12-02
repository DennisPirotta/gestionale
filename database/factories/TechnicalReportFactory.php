<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
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
            'customer_id' => Customer::all()->random()->id,
            'secondary_customer_id' => Customer::all()->random()->id,
            'order_id' => Order::all()->random()->id,
            'number' => fake()->unique()->randomNumber(5),
            'user_id' => User::all()->random()->id,
        ];
    }
}
