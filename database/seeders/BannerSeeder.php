<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Banner;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run()
    {
        $articleId = Article::first()->id;

        DB::table('banners')->insert([
            [
                'title' => 'Bantu Korban Bencana Alam',
                'description' => 'Mari bersama-sama membantu saudara kita yang terdampak bencana alam dengan berdonasi melalui platform ini.',
                'article_id' => null,
                'link' => 'https://amalproduktif.com/donasi/bencana-alam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Peduli Pendidikan Anak Yatim',
                'description' => 'Dukung pendidikan anak-anak yatim dengan memberikan donasi untuk pembelian buku dan perlengkapan sekolah.',
                'article_id' => $articleId,
                'link' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bantu Pembangunan Masjid',
                'description' => 'Mari bersama membangun tempat ibadah untuk masyarakat yang membutuhkan dengan menyisihkan sebagian rezeki kita.',
                'article_id' => null,
                'link' => 'https://amalproduktif.com/donasi/pembangunan-masjid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Santunan untuk Kaum Dhuafa',
                'description' => 'Berikan santunan kepada mereka yang kurang mampu agar bisa merasakan kebahagiaan bersama.',
                'article_id' => $articleId,
                'link' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Donasi untuk Pengobatan Pasien Tidak Mampu',
                'description' => 'Bantu pasien yang membutuhkan biaya pengobatan dengan menyumbangkan sebagian dana Anda.',
                'article_id' => null,
                'link' => 'https://amalproduktif.com/donasi/bantuan-medis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $dummyLogo = [
            'https://img.freepik.com/free-vector/flat-design-charity-event-facebook-cover_23-2149447071.jpg?t=st=1739687888~exp=1739691488~hmac=ea683f010623541b6639db4e30e61635be1e06482c51eae7d8777ec98efb932c&w=1380',
            'https://img.freepik.com/free-vector/flat-minimal-charity-event-facebook-cover_23-2149497669.jpg?t=st=1739688011~exp=1739691611~hmac=ff5fedee555adc44914eda9d3ae3aafde6bcf693504b4d55cae703bbc496d4a7&w=1380',
            'https://img.freepik.com/free-vector/hand-drawn-fundraising-event-facebook-template_23-2150863601.jpg?t=st=1739688034~exp=1739691634~hmac=fcfec53e471aea1723b9ead1e48892b111df41b9a586c73045a23c0b8c7dace6&w=996'
        ];

        $banners = Banner::all();
        foreach ($banners as $banner) {
            $banner->addMediaFromUrl($dummyLogo[array_rand($dummyLogo)])->toMediaCollection('banners');
        }
    }
}
