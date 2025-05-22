<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinancialReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 6; $i++) {
            DB::table('financial_reports')->insert([
                'title' => 'Laporan Keuangan Tahun ' . (2020 + $i),
                'year' => 2020 + $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
