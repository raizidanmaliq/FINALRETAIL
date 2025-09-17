<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocialSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('socials')->insert([
            'images'        => json_encode([
                'back_assets\/img\/cms\/socials\\images31.png',
                'back_assets\/img\/cms\/socials\\images32.png',
                'back_assets\/img\/cms\/socials\\images33.png',
            ]),
            'button_text'   => 'Kunjungi Instagram Kami',
            'button_link'   => 'https://instagram.com/atmius_official',
            'shopee_link'   => 'https://shopee.co.id/atmius_store',
            'tokopedia_link'=> 'https://tokopedia.com/atmius_store',
            'lazada_link'   => 'https://lazada.co.id/shop/atmius',
            'is_active'     => true,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }
}
