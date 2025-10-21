<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PopulateUsernamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = \App\Models\User::whereNull('username')->get();
        
        foreach ($users as $user) {
            // Generate username from name or email
            $baseUsername = strtolower(str_replace(' ', '', $user->name));
            if (empty($baseUsername)) {
                $baseUsername = strtolower(explode('@', $user->email)[0]);
            }
            
            // Ensure username is unique
            $username = $baseUsername;
            $counter = 1;
            while (\App\Models\User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
            
            $user->update(['username' => $username]);
            $this->command->info("Updated user {$user->name} with username: {$username}");
        }
    }
}
