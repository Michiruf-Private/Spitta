<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Shop;
use App\Models\Workshop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workshop_id' => Workshop::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'person_count' => $this->faker->numberBetween(1, 10),
            'notes' => $this->faker->boolean() ? $this->faker->text(100) : null,
        ];
    }
}
