<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
final class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'display_name' => fake()->word(),
            'name' => fake()->unique()->word(),
            'guard_name' => 'web',
            'is_deletable' => true,
            'is_editable' => true,
        ];
    }

    public function deletable($value = true): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_deletable' => $value,
        ]);
    }

    public function editable($value = true): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_editable' => $value,
        ]);
    }
}
