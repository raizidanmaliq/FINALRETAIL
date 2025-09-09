<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('testimonials')->insert([
            [
                'customer_name' => 'Fadli Nugraha',
                'customer_photo' => 'back_assets/img/cms/testimonials/fadli_nugraha.jpg',
                'review' => 'Bahan bajunya adem, pas banget buat dipakai sehari-hari. Sukses terus!',
                'rating' => 5,
                'product_name' => 'Kemeja Polos Basic (Hitam, L)',
                'order_date' => Carbon::parse('2024-05-15'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Siti Aminah',
                'customer_photo' => 'back_assets/img/cms/testimonials/siti_aminah.jpg',
                'review' => 'Ukuran yang dikirim tidak sesuai dengan pesanan saya. Mohon lebih teliti lagi.',
                'rating' => 3,
                'product_name' => 'Gaun Floral Maxi (Merah Muda, M)',
                'order_date' => Carbon::parse('2024-05-20'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Bayu Kusuma',
                'customer_photo' => 'back_assets/img/cms/testimonials/bayu_kusuma.jpg',
                'review' => 'Pelayanannya ramah, respons cepat, dan kualitas produknya bagus sekali. Puas!',
                'rating' => 4,
                'product_name' => 'Celana Jeans Slim Fit (Biru, S)',
                'order_date' => Carbon::parse('2024-05-25'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Rina Aprilia',
                'customer_photo' => 'back_assets/img/cms/testimonials/rina_aprilia.jpg',
                'review' => 'Pengiriman agak lambat dari estimasi, tapi produknya sesuai deskripsi.',
                'rating' => 1,
                'product_name' => 'Jaket Hoodie (Abu-abu, XL)',
                'order_date' => Carbon::parse('2024-05-28'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'customer_name' => 'Agus Budiman',
                'customer_photo' => 'back_assets/img/cms/testimonials/agus_budiman.jpg',
                'review' => 'Harga terjangkau dengan kualitas yang oke. Saya akan belanja lagi di sini.',
                'rating' => 2,
                'product_name' => 'Kaos Katun O-Neck (Putih, M)',
                'order_date' => Carbon::parse('2024-06-01'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
