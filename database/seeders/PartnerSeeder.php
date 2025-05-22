<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('partners')->insert([
            [
                'name' => 'Yayasan Peduli Sesama',
                'description' => 'Organisasi yang bergerak dalam bantuan sosial untuk masyarakat kurang mampu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rumah Harapan',
                'description' => 'Lembaga yang menyediakan tempat tinggal sementara bagi anak-anak yang membutuhkan perawatan medis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bakti Pendidikan',
                'description' => 'Menyediakan bantuan pendidikan bagi anak-anak dari keluarga kurang mampu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pangan Berbagi',
                'description' => 'Program distribusi makanan bagi keluarga yang membutuhkan di daerah terpencil.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sahabat Lansia',
                'description' => 'Organisasi yang memberikan perhatian dan bantuan bagi lansia terlantar.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $dummyLogo = [
            'https://img.freepik.com/free-vector/octagon-letter-gradient-logo_343694-1447.jpg',
            'https://cdn.pixabay.com/photo/2020/08/05/13/27/eco-5465459_640.png',
        ];

        $partners = Partner::all();

        foreach ($partners as $partner) {
            $partner->addMediaFromUrl($dummyLogo[array_rand($dummyLogo)])->toMediaCollection('partners');
        }
    }
}
