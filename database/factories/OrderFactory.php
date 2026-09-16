<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plan = fake()->randomElement(['monthly', 'yearly']);
        $amount = $plan === 'monthly' ? 49000 : 399000;

        return [
            'order_code' => 'PRO'.fake()->unique()->numberBetween(100000, 999999),
            'plan' => $plan,
            'amount' => $amount,
            'bank_code' => fake()->randomElement(['MB', 'VCB', 'TCB', 'ACB']),
            'status' => fake()->randomElement(['pending', 'paid', 'cancelled']),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->numerify('09########'),
        ];
    }

    /**
     * Indicate that the order is paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}
