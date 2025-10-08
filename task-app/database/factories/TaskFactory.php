<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $statuses = ['Pending','In Progress','Completed'];
        $priorities = ['High','Medium','Low'];
        $dueDate = $this->faker->optional()->dateTimeBetween('-1 week', '+2 weeks');
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'due_date' => $dueDate ? $dueDate->format('Y-m-d') : null,
            'status' => $this->faker->randomElement($statuses),
            'priority' => $this->faker->randomElement($priorities),
            'user_id' => User::factory(),
            'assigned_user_id' => null,
        ];
    }
}
