<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['TODO', 'COMPLETED', 'PROGRESS']),
            'priority' =>  $this->faker->randomElement(['LOW', 'MEDIUM', 'HIGH']),
            'project_id' => Project::factory(),
        ];
    }
}
