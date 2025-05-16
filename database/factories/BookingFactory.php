<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition()
    {
        return [
            'property_id' => $this->faker->randomNumber(5),
            'order_id' => Transaction::factory(),
            'room_id' => $this->faker->randomNumber(5),
            'check_in_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'check_out_at' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'created_by' => $this->faker->randomNumber(5),
            'updated_by' => $this->faker->randomNumber(5),
            'status' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }
}
