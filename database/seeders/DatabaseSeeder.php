<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(UserSeeder::class);
        // $this->call(FaqSeeder::class);
        // $this->call(PartnerSeeder::class);
        // $this->call(DonationSeeder::class);
        // $this->call(ArticleSeeder::class);
        // $this->call(BannerSeeder::class);
        // $this->call(TransactionSeeder::class);
        // $this->call(TestimonialSeeder::class);
        // $this->call(AnnualReportSeeder::class);
        // $this->call(FinancialReportSeeder::class);
        // $this->call(MonthlyReportSeeder::class);
        // $this->call(ReportSeeder::class);
    }
}
