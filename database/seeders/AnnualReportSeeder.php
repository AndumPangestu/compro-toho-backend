<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AnnualReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('annual_reports')->insert([
            [
                'title' => 'Laporan Donasi 2020',
                'year' => 2020,
                'collected_funds' => 150000000,
                'donor_count' => 1200,
                'active_program_count' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laporan Donasi 2021',
                'year' => 2021,
                'collected_funds' => 180000000,
                'donor_count' => 1350,
                'active_program_count' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laporan Donasi 2022',
                'year' => 2022,
                'collected_funds' => 200000000,
                'donor_count' => 1500,
                'active_program_count' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laporan Donasi 2023',
                'year' => 2023,
                'collected_funds' => 220000000,
                'donor_count' => 1650,
                'active_program_count' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laporan Donasi 2024',
                'year' => 2024,
                'collected_funds' => 250000000,
                'donor_count' => 1800,
                'active_program_count' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laporan Donasi 2025',
                'year' => 2025,
                'collected_funds' => 250000000,
                'donor_count' => 1800,
                'active_program_count' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
