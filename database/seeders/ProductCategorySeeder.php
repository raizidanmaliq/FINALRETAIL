<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cms\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Laki-laki'],
            ['name' => 'Perempuan'],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
