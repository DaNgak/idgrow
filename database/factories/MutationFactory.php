<?php

namespace Database\Factories;

use App\Models\Mutation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mutation>
 */
class MutationFactory extends Factory
{
    protected $model = Mutation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'user_id' => null, // Akan diisi nanti saat seeder
            'product_id' => null, // Akan diisi nanti saat seeder
            'date' => $this->faker->date(),
            'type' => $this->faker->randomElement(['in', 'out']),
            'quantity' => $this->faker->numberBetween(1, 100),
        ];
    }
}
