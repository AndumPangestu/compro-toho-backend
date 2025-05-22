<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reports')->insert([
            [
                'online_funds' => 150000000,
                'offline_funds' => 10000000,
                'beneficiary_count' => 100,
                'donor_count' => 200,
                'active_program' => 25,
                'coverage_area' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
