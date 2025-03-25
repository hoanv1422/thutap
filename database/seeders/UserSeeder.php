<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Tạo Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'avatar' => 'https://via.placeholder.com/150',
            'phone' => '0123456789',
            'role' => 'admin',
        ]);

        // Tạo 10 người dùng giả
        User::factory(10)->create();
    }
}
