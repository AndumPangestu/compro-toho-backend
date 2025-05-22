<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        DB::table('testimonials')->insert([
            [
                'message' => 'Saya merasa senang bisa berbagi dengan mereka yang membutuhkan. Semoga bantuan kecil ini bisa memberikan manfaat besar bagi mereka.',
                'sender_name' => 'Andi Pratama',
                'organization' => null,
                'sender_category' => 'donor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'message' => 'Bekerja sama dengan Amal Produktif telah membantu kami menjangkau lebih banyak orang yang membutuhkan. Terima kasih atas kerja samanya!',
                'sender_name' => 'Siti Rahmawati',
                'organization' => 'Yayasan Peduli Sesama',
                'sender_category' => 'partner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'message' => 'Terima kasih kepada semua donatur yang telah membantu biaya pengobatan saya. Semoga Allah membalas kebaikan kalian semua.',
                'sender_name' => 'Budi Santoso',
                'organization' => null,
                'sender_category' => 'recipient',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'message' => 'Sebagai mitra, kami sangat mengapresiasi transparansi dan profesionalisme dalam penyaluran donasi. Semoga semakin banyak orang yang terbantu!',
                'sender_name' => 'Dewi Lestari',
                'organization' => 'Lembaga Kemanusiaan Indonesia',
                'sender_category' => 'partner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'message' => 'Saya sangat bersyukur telah menerima bantuan pendidikan ini. Kini saya bisa melanjutkan sekolah tanpa khawatir.',
                'sender_name' => 'Aisyah Putri',
                'organization' => null,
                'sender_category' => 'recipient',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $testimonials = Testimonial::all();

        foreach ($testimonials as $testimonial) {
            $testimonial->addMediaFromUrl('https://vocalvideo.com/resources/content/images/2023/08/nonprofit-testimonials--8-.png')->toMediaCollection('testimonials');
        }
    }
}
