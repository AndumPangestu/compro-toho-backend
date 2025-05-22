<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MonthlyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $months = ['Januari', 'Februari'];

        for ($i = 0; $i < 10; $i++) {
            DB::table('monthly_reports')->insert([
                'title' => 'Laporan Bulanan ' . $months[$i % 2] . ' ' . 2025,
                'year' => 2025,
                'month' => $months[$i % 2],
                'total_expenses' => rand(500000, 5000000),
                'category_id' => rand(1, 5), // Pastikan ada kategori dengan ID 1-5
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
