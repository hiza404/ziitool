<?php

namespace Database\Factories;

use App\Models\ToolOverride;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ToolOverride>
 */
class ToolOverrideFactory extends Factory
{
    protected $model = ToolOverride::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'is_active' => true,
            'custom_title' => fake()->words(3, true),
            'custom_badge' => 'Hot',
            'custom_desc' => fake()->sentence(),
        ];
    }

    /**
     * Indicate that the tool is disabled.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
