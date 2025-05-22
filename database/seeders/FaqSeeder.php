<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    public function run()
    {
        DB::table('faqs')->insert([
            [
                'question' => 'Bagaimana cara melakukan donasi?',
                'answer' => 'Anda dapat melakukan donasi dengan memilih kampanye yang ingin didukung, lalu klik tombol "Donasi Sekarang" dan ikuti langkah-langkah pembayaran yang tersedia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Metode pembayaran apa saja yang tersedia?',
                'answer' => 'Kami menyediakan berbagai metode pembayaran seperti transfer bank, e-wallet (OVO, GoPay, Dana), kartu kredit, dan QRIS untuk memudahkan Anda berdonasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apakah donasi yang saya berikan aman?',
                'answer' => 'Ya, kami menjamin keamanan setiap transaksi donasi dengan enkripsi data dan sistem yang telah terverifikasi untuk memastikan dana sampai ke penerima manfaat.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Bisakah saya mendapatkan laporan penggunaan dana?',
                'answer' => 'Tentu! Kami menyediakan laporan transparan mengenai penggunaan dana donasi yang dapat Anda akses melalui halaman kampanye atau email yang dikirimkan secara berkala.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question' => 'Apakah saya bisa berdonasi secara anonim?',
                'answer' => 'Ya, Anda dapat memilih opsi "Donasi Anonim" saat melakukan donasi agar nama Anda tidak ditampilkan di halaman publik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
