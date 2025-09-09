<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Cms\ProductCategory;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan pemeriksaan foreign key untuk sementara agar truncate dapat dilakukan
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus semua data yang ada di tabel terkait untuk reset total
        DB::table('products')->truncate();
        DB::table('product_variants')->truncate();
        DB::table('product_images')->truncate();

        // Aktifkan kembali pemeriksaan foreign key setelah truncate selesai
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data Produk Utama (total 14 produk)
        $products = [
            // Kategori Pakaian Atasan (ID 1)
            [
                'category_id' => 1,
                'name' => 'Kaos Polos Cotton Combed',
                'sku' => 'TSHIR001',
                'description' => 'Kaos polos dengan bahan Cotton Combed 100% berkualitas tinggi. Nyaman dipakai sehari-hari karena adem, lembut di kulit, serta mampu menyerap keringat dengan baik. Jahitan rapi menggunakan teknik overdeck + rantai, sehingga lebih awet dan tidak mudah melar.',
                'unit' => 'pcs',
                'stock' => 150,
                'promo_label' => 'Bestseller',
                'cost_price' => 35000.00,
                'selling_price' => 60000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-atasan.jpg',
                'size_details' => "S  : Lebar dada 46 cm | Panjang 66 cm\nM  : Lebar dada 49 cm | Panjang 69 cm",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Kemeja Flanel Kotak-kotak',
                'sku' => 'KFLN002',
                'description' => 'Kemeja flanel berbahan katun flanel tebal & lembut, nyaman dipakai harian maupun acara santai. Motif kotak-kotak klasik yang selalu trend, cocok untuk gaya casual, semi formal, hingga hangout.',
                'unit' => 'pcs',
                'stock' => 80,
                'promo_label' => 'New Arrival',
                'cost_price' => 75000.00,
                'selling_price' => 120000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-atasan.jpg',
                'size_details' => "M  : Lebar dada 50 cm | Panjang 70 cm | Panjang lengan 60 cm\nL  : Lebar dada 53 cm | Panjang 72 cm | Panjang lengan 61 cm",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Blouse Katun Lengan Balon',
                'sku' => 'BLOUSE003',
                'description' => 'Blouse wanita berbahan katun premium dengan desain lengan balon yang elegan. Nyaman dipakai sehari-hari maupun acara santai. Model simple namun tetap fashionable, cocok dipadukan dengan celana jeans, rok, atau kulot.',
                'unit' => 'pcs',
                'stock' => 75,
                'promo_label' => 'Bestseller',
                'cost_price' => 60000.00,
                'selling_price' => 115000.00,
                'is_displayed' => true,
                'gender' => 'Wanita',
                'size_chart_image' => 'back_assets/img/products/size-chart-atasan.jpg',
                'size_details' => 'S  : Lingkar dada 90 cm | Panjang 60 cm | Panjang lengan 55 cm
                M  : Lingkar dada 94 cm | Panjang 62 cm | Panjang lengan 56 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Kemeja Oxford Pria',
                'sku' => 'KEMOX004',
                'description' => 'Kemeja pria berbahan Oxford premium dengan tekstur lembut, adem, dan rapi dipakai. Cocok untuk tampilan formal maupun casual smart. Bisa digunakan untuk kerja, meeting, kuliah, atau hangout. Desain simple & elegan membuat penampilan lebih stylish.',
                'unit' => 'pcs',
                'stock' => 95,
                'promo_label' => 'Flash Sale',
                'cost_price' => 85000.00,
                'selling_price' => 150000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-atasan.jpg',
                'size_details' => 'L  : Lingkar dada 104 cm | Panjang 72 cm | Panjang lengan 61 cm
XL : Lingkar dada 108 cm | Panjang 74 cm | Panjang lengan 62 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Pakaian Bawahan (ID 2)
            [
                'category_id' => 2,
                'name' => 'Celana Jeans Slim Fit Pria',
                'sku' => 'JEANS005',
                'description' => 'Celana jeans pria model slim fit dengan potongan modern yang mengikuti bentuk kaki tanpa terasa ketat. Terbuat dari bahan denim stretch berkualitas, nyaman dipakai seharian, tidak mudah pudar, dan tahan lama. Cocok untuk dipadukan dengan kaos, kemeja, maupun jaket untuk gaya casual maupun semi formal.',
                'unit' => 'pcs',
                'stock' => 90,
                'promo_label' => 'Flash Sale',
                'cost_price' => 90000.00,
                'selling_price' => 170000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-celana.jpg',
                'size_details' => '28 : Lingkar pinggang 72 cm | Panjang 95 cm | Lingkar paha 52 cm
32 : Lingkar pinggang 80 cm | Panjang 99 cm | Lingkar paha 56 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Celana Chino Wanita',
                'sku' => 'CHINO006',
                'description' => 'Celana chino wanita berbahan katun twill premium yang adem, halus, dan nyaman dipakai seharian. Model slim fit dengan potongan rapi sehingga memberi kesan elegan namun tetap casual.',
                'unit' => 'pcs',
                'stock' => 60,
                'promo_label' => 'New Arrival',
                'cost_price' => 70000.00,
                'selling_price' => 130000.00,
                'is_displayed' => true,
                'gender' => 'Wanita',
                'size_chart_image' => 'back_assets/img/products/size-chart-celana.jpg',
                'size_details' => '28 : Lingkar pinggang 72 cm | Panjang 95 cm | Lingkar paha 52 cm
29 : Lingkar pinggang 76 cm | Panjang 94 cm | Lingkar paha 54 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Rok Midi Plisket',
                'sku' => 'ROKPL007',
                'description' => 'Rok midi plisket berbahan satin premium dengan tekstur halus, jatuh, dan ringan. Model plisket kekinian memberi kesan anggun dan feminim, cocok dipakai untuk acara santai maupun formal.',
                'unit' => 'pcs',
                'stock' => 55,
                'promo_label' => 'Bestseller',
                'cost_price' => 55000.00,
                'selling_price' => 95000.00,
                'is_displayed' => true,
                'gender' => 'Wanita',
                'size_chart_image' => 'back_assets/img/products/size-chart-rok.jpg',
                'size_details' => 'Rok ini berukuran All Size dengan pinggang elastis. Pas untuk ukuran S hingga L.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Outerwear (ID 3)
            [
                'category_id' => 3,
                'name' => 'Jaket Bomber Parasut',
                'sku' => 'JKTBOM008',
                'description' => 'Jaket bomber berbahan parasut premium dengan lapisan dalam lembut, ringan namun tetap hangat. Desain klasik dengan resleting depan, saku kanan-kiri, serta rib pada pergelangan tangan & pinggang agar tetap fit.',
                'unit' => 'pcs',
                'stock' => 45,
                'promo_label' => 'Flash Sale',
                'cost_price' => 100000.00,
                'selling_price' => 180000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-jaket.jpg',
                'size_details' => 'M : Lebar dada 54 cm | Panjang badan 66 cm | Panjang lengan 60 cm
L : Lebar dada 56 cm | Panjang badan 68 cm | Panjang lengan 61 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Cardigan Rajut Oversize',
                'sku' => 'CARDG009',
                'description' => 'Cardigan rajut dengan model oversize yang sedang tren. Bahan rajut tebal namun tetap adem, nyaman dipakai harian. Desain simple dengan potongan longgar, cocok untuk gaya santai, hangout, hingga dipadukan dengan inner favorit.',
                'unit' => 'pcs',
                'stock' => 40,
                'promo_label' => 'Limited Stock',
                'cost_price' => 75000.00,
                'selling_price' => 140000.00,
                'is_displayed' => true,
                'gender' => 'Wanita',
                'size_chart_image' => 'back_assets/img/products/size-chart-cardigan.jpg',
                'size_details' => 'All Size : Lingkar dada 120 cm | Panjang badan 70 cm | Panjang lengan 55 cm
',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Blazer Formal Wanita',
                'sku' => 'BLAZER010',
                'description' => 'Blazer wanita dengan potongan formal dan elegan, cocok untuk acara kerja, meeting, maupun acara resmi. Terbuat dari bahan polyester premium dengan sedikit stretch, nyaman dipakai seharian tanpa mudah kusut. Desain slim fit mengikuti lekuk tubuh, memberi kesan profesional sekaligus stylish.',
                'unit' => 'pcs',
                'stock' => 30,
                'promo_label' => 'New Arrival',
                'cost_price' => 130000.00,
                'selling_price' => 220000.00,
                'is_displayed' => true,
                'gender' => 'Wanita',
                'size_chart_image' => 'back_assets/img/products/size-chart-blazer.jpg',
                'size_details' => 'S : Lingkar dada 84 cm | Panjang badan 65 cm | Panjang lengan 57 cm
M : Lingkar dada 88 cm | Panjang badan 66 cm | Panjang lengan 58 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Aksesori (ID 4)
            [
                'category_id' => 4,
                'name' => 'Tas Selempang Kulit Sintetis',
                'sku' => 'TASSEL011',
                'description' => 'Tas selempang berbahan kulit sintetis premium dengan finishing halus dan tampilan elegan. Cocok digunakan untuk aktivitas sehari-hari, kuliah, kerja, atau hangout. Dilengkapi dengan tali selempang adjustable sehingga bisa diatur panjang-pendeknya sesuai kenyamanan. Kompak namun tetap muat untuk barang penting seperti dompet, HP, charger, hingga buku kecil. Desain modern yang mudah dipadukan dengan berbagai outfit.',
                'unit' => 'pcs',
                'stock' => 100,
                'promo_label' => 'Bestseller',
                'cost_price' => 80000.00,
                'selling_price' => 150000.00,
                'is_displayed' => true,
                'gender' => 'Wanita',
                'size_chart_image' => null,
                'size_details' => 'Ukuran P x L x T: 25cm x 15cm x 5cm.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Ikat Pinggang Kulit Pria',
                'sku' => 'IKATPN012',
                'description' => 'Ikat pinggang pria berbahan kulit sintetis premium dengan tekstur halus dan kuat. Dilengkapi dengan gesper metal elegan yang kokoh dan tahan lama. Cocok digunakan untuk acara formal maupun casual, memberi kesan rapi dan maskulin. Tampilan simpel namun tetap stylish, mudah dipadukan dengan celana jeans, chino, ataupun bahan.',
                'unit' => 'pcs',
                'stock' => 60,
                'promo_label' => 'Flash Sale',
                'cost_price' => 50000.00,
                'selling_price' => 95000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-ikatpinggang.jpg',
                'size_details' => 'Ukuran tersedia dalam panjang 100cm dan 110cm.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 4,
                'name' => 'Topi Baseball Katun',
                'sku' => 'TOPI013',
                'description' => 'Topi baseball berbahan katun twill premium yang adem dan nyaman dipakai. Desain six panel klasik dengan ventilasi lubang kecil untuk sirkulasi udara. Dilengkapi dengan strap belakang adjustable, sehingga bisa disesuaikan dengan ukuran kepala. Cocok dipakai untuk aktivitas outdoor, olahraga, hangout, hingga melengkapi gaya casual sehari-hari.',
                'unit' => 'pcs',
                'stock' => 85,
                'promo_label' => 'Flash Sale',
                'cost_price' => 40000.00,
                'selling_price' => 75000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-topi.jpg',
                'size_details' => 'Lingkar kepala : 56 – 60 cm (adjustable)
Tinggi crown   : 12 cm
Panjang visor  : 7 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kategori Sepatu (ID 5)
            [
                'category_id' => 5,
                'name' => 'Sneakers Putih Klasik',
                'sku' => 'SNEAKERS014',
                'description' => 'Sneakers putih klasik dengan desain minimalis dan timeless. Terbuat dari bahan synthetic leather + canvas yang ringan, kuat, dan mudah dibersihkan. Sole karet anti-slip memberi kenyamanan serta keamanan saat dipakai seharian. Cocok digunakan untuk berbagai gaya, mulai dari casual, streetwear, hingga semi-formal. Wajib punya sebagai sepatu basic serbaguna yang mudah dipadukan dengan outfit apa pun.',
                'unit' => 'pasang',
                'stock' => 100,
                'promo_label' => 'Bestseller',
                'cost_price' => 90000.00,
                'selling_price' => 175000.00,
                'is_displayed' => true,
                'gender' => 'Pria',
                'size_chart_image' => 'back_assets/img/products/size-chart-sepatu.jpg',
                'size_details' => '40 : Panjang kaki 25 cm
41 : Panjang kaki 25.5 cm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Memasukkan data ke tabel 'products'
        DB::table('products')->insert($products);

        // Data Varian (ID produk disesuaikan dengan urutan)
        $variants = [
    // Kaos Polos (ID 1)
    ['product_id' => 1, 'color' => 'Hitam', 'size' => 'S'],
    ['product_id' => 1, 'color' => 'Hitam', 'size' => 'M'],
    ['product_id' => 1, 'color' => 'Putih', 'size' => 'M'],
    ['product_id' => 1, 'color' => 'Putih', 'size' => 'S'],

    // Kemeja Flanel (ID 2)
    ['product_id' => 2, 'color' => 'Merah', 'size' => 'M'],
    ['product_id' => 2, 'color' => 'Merah', 'size' => 'L'],
    ['product_id' => 2, 'color' => 'Biru', 'size' => 'L'],
    ['product_id' => 2, 'color' => 'Biru', 'size' => 'M'],

    // Blouse Katun Lengan Balon (ID 3)
    ['product_id' => 3, 'color' => 'Putih', 'size' => 'S'],
    ['product_id' => 3, 'color' => 'Putih', 'size' => 'M'],
    ['product_id' => 3, 'color' => 'Hitam', 'size' => 'M'],
    ['product_id' => 3, 'color' => 'Hitam', 'size' => 'S'],

    // Kemeja Oxford Pria (ID 4)
    ['product_id' => 4, 'color' => 'Biru', 'size' => 'L'],
    ['product_id' => 4, 'color' => 'Biru', 'size' => 'XL'],
    ['product_id' => 4, 'color' => 'Putih', 'size' => 'XL'],
    ['product_id' => 4, 'color' => 'Putih', 'size' => 'L'],

    // Celana Jeans Slim Fit Pria (ID 5)
    ['product_id' => 5, 'color' => 'Biru Tua', 'size' => '28'],
    ['product_id' => 5, 'color' => 'Biru Tua', 'size' => '32'],
    ['product_id' => 5, 'color' => 'Hitam', 'size' => '32'],
    ['product_id' => 5, 'color' => 'Hitam', 'size' => '28'],

    // Celana Chino Wanita (ID 6)
    ['product_id' => 6, 'color' => 'Beige', 'size' => '28'],
    ['product_id' => 6, 'color' => 'Beige', 'size' => '29'],
    ['product_id' => 6, 'color' => 'Hitam', 'size' => '28'],
    ['product_id' => 6, 'color' => 'Hitam', 'size' => '29'],

    // Rok Midi Plisket (ID 7)
    ['product_id' => 7, 'color' => 'Hitam', 'size' => 'All Size'],
    ['product_id' => 7, 'color' => 'Cokelat', 'size' => 'All Size'],

    // Jaket Bomber Parasut (ID 8)
    ['product_id' => 8, 'color' => 'Biru Dongker', 'size' => 'M'],
    ['product_id' => 8, 'color' => 'Biru Dongker', 'size' => 'L'],
    ['product_id' => 8, 'color' => 'Hitam', 'size' => 'L'],
    ['product_id' => 8, 'color' => 'Hitam', 'size' => 'M'],

    // Cardigan Rajut Oversize (ID 9)
    ['product_id' => 9, 'color' => 'Cream', 'size' => 'All Size'],
    ['product_id' => 9, 'color' => 'Abu-abu', 'size' => 'All Size'],

    // Blazer Formal Wanita (ID 10)
    ['product_id' => 10, 'color' => 'Hitam', 'size' => 'S'],
    ['product_id' => 10, 'color' => 'Hitam', 'size' => 'M'],
    ['product_id' => 10, 'color' => 'Navy', 'size' => 'M'],
    ['product_id' => 10, 'color' => 'Navy', 'size' => 'S'],

    // Tas Selempang Kulit Sintetis (ID 11)
    ['product_id' => 11, 'color' => 'Hitam', 'size' => 'All Size'],
    ['product_id' => 11, 'color' => 'Cokelat', 'size' => 'All Size'],

    // Ikat Pinggang Kulit Pria (ID 12)
    ['product_id' => 12, 'color' => 'Hitam', 'size' => '100cm'],
    ['product_id' => 12, 'color' => 'Hitam', 'size' => '110cm'],
    ['product_id' => 12, 'color' => 'Cokelat', 'size' => '110cm'],
    ['product_id' => 12, 'color' => 'Cokelat', 'size' => '100cm'],

    // Topi Baseball Katun (ID 13)
    ['product_id' => 13, 'color' => 'Hitam', 'size' => 'All Size'],
    ['product_id' => 13, 'color' => 'Putih', 'size' => 'All Size'],

    // Sneakers Putih Klasik (ID 14)
    ['product_id' => 14, 'color' => 'Putih', 'size' => '40'],
    ['product_id' => 14, 'color' => 'Putih', 'size' => '41'],
];

        // Memasukkan data ke tabel 'product_variants'
        DB::table('product_variants')->insert($variants);

        // Data Gambar (menggunakan ID produk hardcode dan jalur yang sama)
        $images = [
            // Kaos Polos (ID 1)
            ['product_id' => 1, 'image_path' => 'back_assets/img/products/kaos-polos-1.jpg'],
            ['product_id' => 1, 'image_path' => 'back_assets/img/products/kaos-polos-2.jpg'],
            // Kemeja Flanel (ID 2)
            ['product_id' => 2, 'image_path' => 'back_assets/img/products/kemeja-flanel-1.jpg'],
            ['product_id' => 2, 'image_path' => 'back_assets/img/products/kemeja-flanel-2.jpg'],
            // Blouse Katun Lengan Balon (ID 3)
            ['product_id' => 3, 'image_path' => 'back_assets/img/products/blouse-katun-1.jpg'],
            // Kemeja Oxford Pria (ID 4)
            ['product_id' => 4, 'image_path' => 'back_assets/img/products/kemeja-oxford-1.jpg'],
            ['product_id' => 4, 'image_path' => 'back_assets/img/products/kemeja-oxford-2.jpg'],
            // Celana Jeans Slim Fit Pria (ID 5)
            ['product_id' => 5, 'image_path' => 'back_assets/img/products/celana-jeans-pria-1.jpg'],
            ['product_id' => 5, 'image_path' => 'back_assets/img/products/celana-jeans-pria-2.jpg'],
            // Celana Chino Wanita (ID 6)
            ['product_id' => 6, 'image_path' => 'back_assets/img/products/celana-chino-wanita-1.jpg'],
            ['product_id' => 6, 'image_path' => 'back_assets/img/products/celana-chino-wanita-2.jpg'],
            // Rok Midi Plisket (ID 7)
            ['product_id' => 7, 'image_path' => 'back_assets/img/products/rok-midi-plisket-1.jpg'],
            ['product_id' => 7, 'image_path' => 'back_assets/img/products/rok-midi-plisket-2.jpg'],
            // Jaket Bomber Parasut (ID 8)
            ['product_id' => 8, 'image_path' => 'back_assets/img/products/jaket-bomber-1.jpg'],
            ['product_id' => 8, 'image_path' => 'back_assets/img/products/jaket-bomber-2.jpg'],
            // Cardigan Rajut Oversize (ID 9)
            ['product_id' => 9, 'image_path' => 'back_assets/img/products/cardigan-rajut-1.jpg'],
            ['product_id' => 9, 'image_path' => 'back_assets/img/products/cardigan-rajut-2.jpg'],
            // Blazer Formal Wanita (ID 10)
            ['product_id' => 10, 'image_path' => 'back_assets/img/products/blazer-formal-1.jpg'],
            ['product_id' => 10, 'image_path' => 'back_assets/img/products/blazer-formal-2.jpg'],
            // Tas Selempang Kulit Sintetis (ID 11)
            ['product_id' => 11, 'image_path' => 'back_assets/img/products/tas-selempang-1.jpg'],
            ['product_id' => 11, 'image_path' => 'back_assets/img/products/tas-selempang-2.jpg'],
            // Ikat Pinggang Kulit Pria (ID 12)
            ['product_id' => 12, 'image_path' => 'back_assets/img/products/ikat-pinggang-1.jpg'],
            // Topi Baseball Katun (ID 13)
            ['product_id' => 13, 'image_path' => 'back_assets/img/products/topi-baseball-1.jpg'],
            // Sneakers Putih Klasik (ID 14)
            ['product_id' => 14, 'image_path' => 'back_assets/img/products/sneakers-1.jpg'],
        ];

        // Memasukkan data ke tabel 'product_images'
        DB::table('product_images')->insert($images);
    }
}
