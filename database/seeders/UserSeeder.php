<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User Admin Default
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin POS',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Buat User Staff Default
        User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff Kasir',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );
    }
}