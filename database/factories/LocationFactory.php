<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company,
            'latitude' => round($this->faker->latitude(), 7),
            'longitude' => round($this->faker->longitude(), 7),
            'address' => $this->faker->optional()->streetAddress(),
        ];
    }
}
