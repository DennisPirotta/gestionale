<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $inner = 1;
        static $outer = 1;
        return [
            'company' => Company::all()->random()->id,
            'innerCode' => $inner++,
            'outerCode' => $outer++,
            'description' => fake()->text,
            'status' => Status::all()->random()->id,
            'country' => Country::all()->random()->id,
            'hourSW' => fake()->randomNumber(2),
            'hourMS' => fake()->randomNumber(2),
            'hourFAT' => fake()->randomNumber(2),
            'hourSAF' => fake()->randomNumber(2),
            'progress' => fake()->text,
            'opening' => fake()->dateTime,
            'closing' => fake()->dateTime,
            'customer' => Customer::all()->random()->id,
            'manager' => User::all()->random()->id
        ];
    }
}
