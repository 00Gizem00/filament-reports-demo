<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample users with different creation dates
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(30),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(25),
        ]);

        User::create([
            'name' => 'Michael Johnson',
            'email' => 'michael@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(20),
        ]);

        User::create([
            'name' => 'Sarah Williams',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(15),
        ]);

        User::create([
            'name' => 'David Brown',
            'email' => 'david@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(10),
        ]);

        User::create([
            'name' => 'Emily Davis',
            'email' => 'emily@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(5),
        ]);

        User::create([
            'name' => 'Robert Wilson',
            'email' => 'robert@example.com',
            'password' => Hash::make('password'),
            'created_at' => now()->subDays(2),
        ]);
    }
} 