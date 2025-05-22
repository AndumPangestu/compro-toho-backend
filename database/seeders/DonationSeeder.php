<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Donation;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('donation_categories')->insert([
            ['name' => 'Pendidikan', 'description' => 'Membantu anak-anak mendapatkan pendidikan yang layak', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kesehatan', 'description' => 'Bantuan biaya pengobatan dan kesehatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bencana Alam', 'description' => 'Bantuan bagi korban bencana alam', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sosial', 'description' => 'Bantuan bagi masyarakat kurang mampu', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rumah Ibadah', 'description' => 'Bantuan renovasi dan pembangunan rumah ibadah', 'created_at' => now(), 'updated_at' => now()],
        ]);


        DB::table('donations')->insert([
            [
                'id' => Str::uuid(),
                'category_id' => 1,
                'title' => 'Beasiswa untuk Anak Yatim',
                'slug' => 'beasiswa-untuk-anak-yatim',
                'fund_usage_details' => 'Dana digunakan untuk biaya sekolah dan perlengkapan belajar.',
                'description' => 'Membantu anak-anak yatim mendapatkan pendidikan yang layak.',
                'distribution_information' => 'Dana akan disalurkan ke yayasan pendidikan.',
                'target_amount' => 50000000,
                'collected_amount' => 10000000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(3),
                'location' => 'Jakarta',
                'put_on_highlight' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'category_id' => 2,
                'title' => 'Bantuan Operasi Anak Kurang Mampu',
                'slug' => 'bantuan-operasi-anak',
                'fund_usage_details' => 'Biaya operasi dan perawatan anak penderita penyakit langka.',
                'description' => 'Membantu anak-anak mendapatkan pengobatan yang layak.',
                'distribution_information' => 'Dana akan diberikan langsung ke rumah sakit.',
                'target_amount' => 100000000,
                'collected_amount' => 25000000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(4),
                'location' => 'Bandung',
                'put_on_highlight' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'category_id' => 3,
                'title' => 'Bantuan Korban Gempa',
                'slug' => 'bantuan-korban-gempa',
                'fund_usage_details' => 'Bantuan makanan, pakaian, dan tempat tinggal sementara.',
                'description' => 'Menolong korban gempa mendapatkan bantuan darurat.',
                'distribution_information' => 'Dana akan disalurkan melalui organisasi kemanusiaan.',
                'target_amount' => 200000000,
                'collected_amount' => 50000000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'location' => 'Yogyakarta',
                'put_on_highlight' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'category_id' => 4,
                'title' => 'Bantuan Sembako untuk Masyarakat',
                'slug' => 'bantuan-sembako',
                'fund_usage_details' => 'Paket sembako untuk keluarga kurang mampu.',
                'description' => 'Membantu masyarakat mendapatkan kebutuhan pokok.',
                'distribution_information' => 'Dana akan digunakan untuk membeli dan mendistribusikan sembako.',
                'target_amount' => 75000000,
                'collected_amount' => 15000000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(1),
                'location' => 'Surabaya',
                'put_on_highlight' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'category_id' => 5,
                'title' => 'Renovasi Masjid Al-Ikhlas',
                'slug' => 'renovasi-masjid-al-ikhlas',
                'fund_usage_details' => 'Dana digunakan untuk renovasi atap dan lantai masjid.',
                'description' => 'Membantu renovasi masjid agar lebih nyaman untuk ibadah.',
                'distribution_information' => 'Dana akan diserahkan ke pengurus masjid.',
                'target_amount' => 120000000,
                'collected_amount' => 30000000,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(5),
                'location' => 'Medan',
                'put_on_highlight' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $dummyImage = [
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQFZKXG4saaW3zyv3F9paphY8YYytP-mlq3xw&s',
            'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-qS8z_03OdrI3n8fUdynIoXjw1vjkfPyvtQ&s',
            'https://www.investopedia.com/thmb/hvCs7nGRZ539vKETw1KIHvk2HzM=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/GettyImages-1173117669-baa23a3889054f828aebc58f9de136b6.jpg',
        ];

        $donations = Donation::all();
        foreach ($donations as $donation) {
            $donation->addMediaFromUrl($dummyImage[array_rand($dummyImage)])->toMediaCollection('donations');
        }
    }
}
