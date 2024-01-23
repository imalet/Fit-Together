<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InformationComplementaire>
 */
class InformationComplementaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "user_id" => 1,
            "bio" => "Ma bio",
            "qualification" => "Ma Qualification",
            "experience" => "Mon Experience",
        ];
    }
}
