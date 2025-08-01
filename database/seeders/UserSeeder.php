<?php

namespace Database\Seeders;

use App\Models\AnonymousDonor;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' => Str::uuid(),
            'name' => 'SuperAdmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'superadmin',

        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),

        ]);
    }
}
