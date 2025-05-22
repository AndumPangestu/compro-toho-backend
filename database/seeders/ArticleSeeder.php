<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Donation;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $categories = [
            'Donasi',
            'Sosial',
            'Kemiskinan',
            'Bencana Alam',
            'Kemanusiaan'
        ];

        foreach ($categories as $key => $category) {
            DB::table('article_categories')->insert([
                'id' => $key + 1,
                'name' => $category,
                'description' => "Ini adalah category artikel untuk $category",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Seeder for donation campaign articles
        $articles = [
            [
                'title' => 'Bantu Korban Banjir Jakarta',
                'slug' => 'bantu-korban-banjir-jakarta',
                'type' => 'news',
                'category_id' => 4,
                'description' => 'Donasi untuk membantu korban banjir Jakarta.',
                'content' => 'Ribuan warga terdampak banjir di Jakarta. Mari bantu mereka dengan berdonasi.',
                'put_on_highlight' => true
            ],
            [
                'title' => 'Peduli Anak Yatim dan Dhuafa',
                'slug' => 'peduli-anak-yatim-dhuafa',
                'type' => 'kindness_story',
                'category_id' => 3,
                'description' => 'Donasi untuk anak yatim dan dhuafa agar mendapatkan pendidikan dan kehidupan yang lebih baik.',
                'content' => 'Banyak anak yatim dan dhuafa membutuhkan bantuan kita. Mari ulurkan tangan untuk mereka.',
                'put_on_highlight' => false
            ],
            [
                'title' => 'Bangun Sekolah untuk Anak Pedalaman',
                'slug' => 'bangun-sekolah-anak-pedalaman',
                'type' => 'release',
                'category_id' => 1,
                'description' => 'Donasi untuk pembangunan sekolah di daerah pedalaman.',
                'content' => 'Akses pendidikan masih sulit di beberapa daerah pedalaman. Mari bersama membangun sekolah.',
                'put_on_highlight' => true
            ],
            [
                'title' => 'Bantu Pasien Kanker Mendapatkan Pengobatan',
                'slug' => 'bantu-pasien-kanker',
                'type' => 'infographics',
                'category_id' => 2,
                'description' => 'Donasi untuk pasien kanker yang membutuhkan biaya pengobatan.',
                'content' => 'Pengobatan kanker memerlukan biaya besar. Mari bantu pasien kanker mendapatkan perawatan.',
                'put_on_highlight' => false
            ],
            [
                'title' => 'Gerakan 1.000 Paket Sembako untuk Keluarga Miskin',
                'slug' => 'gerakan-1000-paket-sembako',
                'type' => 'news',
                'category_id' => 3,
                'description' => 'Aksi sosial pembagian 1.000 paket sembako bagi keluarga miskin.',
                'content' => 'Banyak keluarga masih hidup dalam kesulitan. Mari berbagi sembako untuk mereka.',
                'put_on_highlight' => true
            ]
        ];

        $donationId = Donation::first()->id;



        foreach ($articles as $article) {
            $articleId = Str::uuid();
            DB::table('articles')->insert([
                'id' => $articleId,
                'title' => $article['title'],
                'slug' => $article['slug'],
                'type' => $article['type'],
                'category_id' => $article['category_id'],
                'donation_id' => $donationId,
                'description' => $article['description'],
                'content' => $article['content'],
                'put_on_highlight' => $article['put_on_highlight'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Seeder for article tags
            $tags = explode(', ', 'donasi, sosial, kemanusiaan, pendidikan, kesehatan, bencana alam, anak yatim, kemiskinan');
            foreach ($tags as $tag) {
                $tagId = DB::table('tags')->updateOrInsert([
                    'name' => $tag
                ], [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $tagId = DB::table('tags')->where('name', $tag)->value('id');
                DB::table('article_tag')->insert([
                    'article_id' => $articleId,
                    'tag_id' => $tagId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $dummyImage = [
            'https://cdn0-production-images-kly.akamaized.net/sCdKtKghnm2CNcZZa_0fYKmSsOo=/0x86:1600x987/800x450/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/4725852/original/090577600_1706155382-Ilustrasi_kemanusiaan__solidaritas__toleransi.jpg',
            'https://media.kompas.tv/library/image/content_article/article_img/20231018070916.jpg',
            'https://cdn0-production-images-kly.akamaized.net/vmKYF224C355sF8zzPJR_0SUNoA=/800x450/smart/filters:quality(75):strip_icc():format(webp)/kly-media-production/medias/4658782/original/008928400_1700646378-diverse-people-doing-fist-bump-park.jpg'
        ];

        $articles = Article::all();
        foreach ($articles as $article) {
            $article->addMediaFromUrl($dummyImage[array_rand($dummyImage)])->toMediaCollection('articles');
        }
    }
}
