<?php

namespace Database\Factories;

use App\Models\Postponement;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostponementFactory extends Factory
{
    protected $model = Postponement::class;

    public function definition(): array
    {
        $old = $this->faker->dateTimeBetween('-2 weeks', 'now');
        $new = $this->faker->dateTimeBetween('now', '+2 weeks');
        return [
            'task_id' => Task::factory(),
            'old_due_date' => $old->format('Y-m-d'),
            'new_due_date' => $new->format('Y-m-d'),
            'reason' => $this->faker->sentence(),
            'postponed_by' => User::factory(),
        ];
    }
}
