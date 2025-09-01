<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformationPagesSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Hapus entri yang ada untuk menghindari duplikasi saat seeding
        DB::table('information_pages')->whereIn('slug', [
            'privacy-policy',
            'terms-and-conditions',
            'general-settings'
        ])->delete();

        // Tambahkan halaman-halaman yang dibutuhkan
        DB::table('information_pages')->insert([
            [
                'slug' => 'privacy-policy',
                'title' => 'Kebijakan Privasi',
                'content' => 'Ini adalah halaman Kebijakan Privasi. Silakan perbarui konten Anda di sini.',
                'company_name' => null,
                'company_tagline' => null,
                'whatsapp' => null,
                'email' => null,
                'address' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'tiktok_url' => null,
                'youtube_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Syarat & Ketentuan',
                'content' => 'Ini adalah halaman Syarat & Ketentuan. Silakan perbarui konten Anda di sini.',
                'company_name' => null,
                'company_tagline' => null,
                'whatsapp' => null,
                'email' => null,
                'address' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'tiktok_url' => null,
                'youtube_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'slug' => 'general-settings',
                'title' => 'Pengaturan Umum',
                'content' => null, // Konten diset null karena halaman ini menggunakan kolom terpisah
                'company_name' => 'Ahlinya Retail',
                'company_tagline' => 'Toko Pakaian Online Dengan Koleksi Terkini Dengan Kualitas Premium',
                'whatsapp' => '6281234567890',
                'email' => 'email@tokokami.com',
                'address' => 'Jalan Kenangan No. 123, Jakarta',
                'facebook_url' => 'https://facebook.com/tokokami',
                'instagram_url' => 'https://instagram.com/tokokami',
                'tiktok_url' => 'https://tiktok.com/@tokokami',
                'youtube_url' => 'https://youtube.com/tokokami',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
