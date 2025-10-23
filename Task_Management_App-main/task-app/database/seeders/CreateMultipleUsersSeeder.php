<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateMultipleUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create multiple test users
        $users = [
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Manager User',
                'username' => 'manager',
                'email' => 'manager@example.com',
                'password' => Hash::make('password'),
                'role' => 'manager',
            ],
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'janesmith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        $this->command->info('Multiple test users created successfully!');
    }
}