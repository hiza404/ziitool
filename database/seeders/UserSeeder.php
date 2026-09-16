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
        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@microtools.com'],
            [
                'name' => 'Quản Trị Viên',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // Standard Demo User
        User::updateOrCreate(
            ['email' => 'user@microtools.com'],
            [
                'name' => 'Người Dùng Tiêu Chuẩn',
                'password' => Hash::make('user123'),
                'is_admin' => false,
            ]
        );
    }
}
