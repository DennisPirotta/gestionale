<?php

namespace Database\Factories;

use App\Models\Hour;
use App\Models\TechnicalReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechnicalReportDetails>
 */
class TechnicalReportDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'hour_id' => Hour::all()->random()->id,
            'technical_report_id' => TechnicalReport::all()->random()->id,
        ];
    }
}
