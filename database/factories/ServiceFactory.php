<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'slot_duration' => $this->faker->randomNumber(2),
            'capacity' => $this->faker->randomNumber(1),
            'clean_time' => $this->faker->randomNumber(1),
            'booking_time_limit' => $this->faker->randomNumber(1),
            'business_administrator_id' => 1,
        ];
    }
}
