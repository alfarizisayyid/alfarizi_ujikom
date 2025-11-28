<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@polisi.com'],
            [
                'name' => 'Admin Polisi',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create Sample Regular Users
        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => "Calon Pendaftar {$i}",
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
