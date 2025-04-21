<?php

namespace Database\Seeders\Custom;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'User',
                'email'=>'user@admin.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        ];

        User::insertOrIgnore($data);
    }
}
