<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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

        // 2. Buat User Staff Default (Untuk Uji Coba RBAC)
        User::firstOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff Kasir',
                'password' => Hash::make('password'),
                'role' => 'staff',
            ]
        );

        // 3. Buat Kategori Skincare Default
        $categories = ['Serum', 'Moisturizer', 'Sunscreen', 'Facial Wash', 'Toner'];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }
    }
}