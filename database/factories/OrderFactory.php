<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Country;
use App\Models\Customer;
use App\Models\JobType;
use App\Models\Status;
use App\Models\User;
use Exception;
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
     *
     * @throws Exception
     */
    public function definition(): array
    {
        static $inner = 20220001;
        static $outer = 20220001;
        random_int(0, 1) === 1 ? $closing = fake()->dateTime : $closing = null;
        do {
            $status = Status::all()->random()->id;
        } while ($status === 6);
        if ($closing !== null) {
            $status = 6; //chiusa
        }

        return [
            'company_id' => Company::all()->random()->id,
            'innerCode' => $inner++,
            'outerCode' => $outer++,
            'description' => fake()->text(10),
            'status_id' => $status,
            'country_id' => Country::all()->random()->id,
            'job_type_id' => JobType::all()->random()->id,
            'opening' => fake()->dateTime,
            'closing' => $closing,
            'customer_id' => Customer::all()->random()->id,
            'user_id' => User::all()->random()->id,
            'created_by' => User::all()->random()->id,
        ];
    }
}
