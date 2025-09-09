<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('banners')->insert([
            [
                'title' => 'Diskon Spesial Akhir Tahun',
                'image' => 'back_assets/img/cms/banners/banner_promo.jpg',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
