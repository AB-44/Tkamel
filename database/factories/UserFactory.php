<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'role_id'       => null,
            'full_name'     => fake()->name(),
            'email'         => fake()->unique()->safeEmail(),
            'password_hash' => Hash::make('password'),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', 'admin')->first()?->id,
        ]);
    }

    public function regularUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::where('name', 'user')->first()?->id,
        ]);
    }
}
