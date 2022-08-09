<?php

namespace Database\Factories;

use App\Models\Company;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Support\Carbon;

/**
 * @extends Factory
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    #[ArrayShape(['name' => "string", 'email' => "string", 'email_verified_at' => Carbon::class, 'password' => "string", 'holidays' => "int", 'level' => "int", 'company' => "\Illuminate\Support\HigherOrderCollectionProxy|mixed", 'remember_token' => "string"])] public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'holidays' => 480,
            'level' => random_int(0, 2),
            'company' => Company::all()->random()->id,
            'remember_token' => Str::random(10),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified(): static
    {
        return $this->state(function () {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
