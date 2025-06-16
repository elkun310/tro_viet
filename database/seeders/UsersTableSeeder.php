<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 1 admin cụ thể
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('Admin@123'),
            'phone' => '0123456789',
            'role' => 'admin',
        ]);

        // Tạo 5 host ngẫu nhiên
        User::factory()->count(5)->create(['role' => 'host']);

        // Tạo 20 user ngẫu nhiên
        User::factory()->count(20)->create(['role' => 'user']);
    }
}
