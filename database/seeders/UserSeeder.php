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
            'email' => 'superadmin@example',
            'password' => bcrypt('123123'),
            'role' => 'superadmin',

        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@example',
            'password' => bcrypt('123123'),
            'role' => 'admin',
            'email_verified_at' => now(),

        ]);

        User::create([
            'id' => Str::uuid(),
            'name' => 'User',
            'email' => 'user@example',
            'password' => bcrypt('123123'),
            'role' => 'user',
        ]);


        AnonymousDonor::create([
            'name' => 'Anonymous Donor',
            'email' => 'anonymous@example',
            'phone' => '081234567890',
        ]);
    }
}
