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
        // Define 5 common fashion categories
        $categories = [
            ['name' => 'Pakaian Atasan'], // Tops
            ['name' => 'Pakaian Bawahan'], // Bottoms
            ['name' => 'Outerwear'], // Jaket, blazer, dsb.
            ['name' => 'Aksesori'], // Tas, ikat pinggang, dsb.
            ['name' => 'Sepatu'], // Shoes
        ];

        // Loop through the array and create the categories if they don't already exist
        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
