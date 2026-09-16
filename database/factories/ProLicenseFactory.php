<?php

namespace Database\Factories;

use App\Models\ProLicense;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ProLicense>
 */
class ProLicenseFactory extends Factory
{
    protected $model = ProLicense::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'PRO-'.strtoupper(Str::random(10)),
            'plan' => fake()->randomElement(['monthly', 'yearly', 'lifetime']),
            'is_active' => true,
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'used_at' => fake()->boolean() ? now()->subDays(fake()->numberBetween(1, 30)) : null,
            'expires_at' => now()->addDays(fake()->numberBetween(30, 365)),
            'notes' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the license is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
