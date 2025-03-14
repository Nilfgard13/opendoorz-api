<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['username' => 'Akmal Hanif', 'email' => 'akmal@example.com', 'role' => 'super admin', 'password' => Hash::make('@Password123'), 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'Faishal Habib', 'email' => 'faishal@example.com', 'role' => 'admin', 'password' => Hash::make('@Password123'), 'created_at' => now(), 'updated_at' => now()],
            ['username' => 'Farid', 'email' => 'farid@example.com', 'role' => 'admin', 'password' => Hash::make('@Password123'), 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($data as $item) {
            User::create($item);
        }
    }
}
