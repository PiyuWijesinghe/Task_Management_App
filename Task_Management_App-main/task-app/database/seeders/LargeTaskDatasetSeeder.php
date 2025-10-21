<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LargeTaskDatasetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a demo user exists
        $user = User::firstOrCreate([
            'email' => 'loadtest@example.com'
        ], [
            'name' => 'Load Test User',
            'username' => 'loadtest',
            'password' => bcrypt('password'),
        ]);

        // Create a small set of other users for many-to-many assignments
        $assignees = [];
        for ($i = 1; $i <= 5; $i++) {
            $assignees[] = User::firstOrCreate([
                'email' => "assignee{$i}@example.com"
            ], [
                'name' => "Assignee {$i}",
                'username' => "assignee{$i}",
                'password' => bcrypt('password'),
            ]);
        }

        $statuses = ['Pending', 'In Progress', 'Completed'];
        $priorities = ['High','Medium','Low'];

        // Create many tasks (e.g., 2000) to test large dataset exports
        $count = 2000;
        $batch = 50;

        for ($i = 0; $i < $count; $i += $batch) {
            $toCreate = [];
            for ($j = 0; $j < $batch && ($i + $j) < $count; $j++) {
                $title = 'Load Test Task ' . ($i + $j + 1) . ' - ' . Str::random(6);
                $due = Carbon::now()->addDays(rand(-30, 60));
                $toCreate[] = [
                    'title' => $title,
                    'description' => 'Auto-generated for load test',
                    'due_date' => $due->format('Y-m-d'),
                    'status' => $statuses[array_rand($statuses)],
                    'priority' => $priorities[array_rand($priorities)],
                    'user_id' => $user->id,
                    'assigned_user_id' => $assignees[array_rand($assignees)]->id,
                    'created_at' => Carbon::now()->subDays(rand(0, 365)),
                    'updated_at' => Carbon::now(),
                ];
            }

            Task::insert($toCreate);
        }

        // Attach some of the many-to-many assigned users randomly
        $allTasks = Task::where('user_id', $user->id)->get();
        foreach ($allTasks as $task) {
            // Attach 0..3 random assignees
            $pick = rand(0,3);
            if ($pick > 0) {
                $sample = collect($assignees)->random($pick)->pluck('id')->toArray();
                $task->assignedUsers()->syncWithoutDetaching($sample);
            }
        }

        $this->command->info("Created {$count} tasks for user {$user->email}");
    }
}
