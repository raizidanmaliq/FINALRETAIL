<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('testimonials')->insert([
            [
                'customer_name' => 'Fadli Nugraha',
                'customer_photo' => 'back_assets/img/cms/testimonials/fadli_nugraha.jpg',
                'review' => 'Bahan bajunya adem, pas banget buat dipakai sehari-hari. Sukses terus!',
                'rating' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Siti Aminah',
                'customer_photo' => 'back_assets/img/cms/testimonials/siti_aminah.jpg',
                'review' => 'Ukuran yang dikirim tidak sesuai dengan pesanan saya. Mohon lebih teliti lagi.',
                'rating' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Bayu Kusuma',
                'customer_photo' => 'back_assets/img/cms/testimonials/bayu_kusuma.jpg',
                'review' => 'Pelayanannya ramah, respons cepat, dan kualitas produknya bagus sekali. Puas!',
                'rating' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Rina Aprilia',
                'customer_photo' => 'back_assets/img/cms/testimonials/rina_aprilia.jpg',
                'review' => 'Pengiriman agak lambat dari estimasi, tapi produknya sesuai deskripsi.',
                'rating' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Agus Budiman',
                'customer_photo' => 'back_assets/img/cms/testimonials/agus_budiman.jpg',
                'review' => 'Harga terjangkau dengan kualitas yang oke. Saya akan belanja lagi di sini.',
                'rating' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
