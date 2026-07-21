<?php

namespace Database\Factories;

use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Workshop>
 */
class WorkshopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Abrechnung nach GOZ kompakt',
                'Hygienemanagement in der Praxis',
                'Notfallmanagement für das Praxisteam',
                'DSGVO in der Arzt- und Zahnarztpraxis',
                'Prophylaxe-Update 2026',
                'Qualitätsmanagement nach QM-Richtlinie',
                'Kommunikation mit Angstpatienten',
                'Röntgen-Aktualisierung Fachkunde',
            ]),
            'price' => (string) $this->faker->randomFloat(2, 10, 100),
        ];
    }
}
