<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            InformationPagesSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            BannerSeeder::class,
            TestimonialSeeder::class,
            HeroSeeder::class,
            CtaSeeder::class,
            SocialSeeder::class,
        ]);
    }
}
