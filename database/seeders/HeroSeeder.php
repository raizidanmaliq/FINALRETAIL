<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HeroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('heroes')->insert([
            'headline' => 'Belanja Mudah, Harga Bersahabat, Kualitas Terjamin',
            'subheadline' => 'Temukan produk terbaik dengan harga yang tidak akan menguras kantong anda!',
            'images' => json_encode([
                'back_assets/img/cms/heroes/hero1.jpg',
                'back_assets/img/cms/heroes/hero2.jpg',
                'back_assets/img/cms/heroes/hero3.jpg',
            ]),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
