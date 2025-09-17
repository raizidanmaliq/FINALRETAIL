<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CtaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ctas')->insert([
            'title' => 'Sudah siap tampil beda? Temukan outfit terbaikmu di sini!',
            'image' => 'back_assets/img/cms/ctas/image7.jpg',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
