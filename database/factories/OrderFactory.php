<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\JobType;
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
        static $inner = 20220001;
        static $outer = 20220001;
        return [
            'company_id' => Company::all()->random()->id,
            'innerCode' => $inner++,
            'outerCode' => $outer++,
            'description' => fake()->text,
            'status_id' => Status::all()->random()->id,
            'country_id' => Country::all()->random()->id,
            'hourSW' => 0,
            'hourMS' => 0,
            'hourFAT' => 0,
            'hourSAF' => 0,
            'job_type_id' => JobType::all()->random()->id,
            'opening' => fake()->dateTime,
            'closing' => fake()->dateTime,
            'customer_id' => Customer::all()->random()->id,
            'user_id' => User::all()->random()->id
        ];
    }
}
