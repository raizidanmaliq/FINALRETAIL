<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformationPagesSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama untuk mencegah duplikasi sebelum menambahkan yang baru
        DB::table('information_pages')->whereIn('slug', [
            'privacy-policy',
            'terms-and-conditions',
            'general-settings'
        ])->delete();

        // Teks deskriptif atau narasi untuk setiap halaman
        $privacyPolicyContent = '
<p>Kebijakan Privasi ini menjelaskan bagaimana Ahlinya Retail mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda ketika Anda menggunakan layanan kami, termasuk website dan aplikasi mobile.</p>

<p>1. Informasi yang Kami Kumpulkan</p>
<p>Kami dapat mengumpulkan informasi pribadi Anda seperti nama, alamat email, nomor telepon, alamat pengiriman, serta data pembayaran. Selain itu, kami juga mengumpulkan informasi non-pribadi seperti alamat IP, jenis browser, halaman yang dikunjungi, dan cookies.</p>

<p>2. Penggunaan Informasi</p>
<p>Informasi Anda digunakan untuk memproses pesanan, mengirimkan notifikasi, personalisasi pengalaman belanja, analisis tren, dan meningkatkan keamanan layanan.</p>

<p>3. Berbagi Informasi</p>
<p>Kami tidak menjual informasi pribadi Anda. Informasi dapat dibagikan kepada penyedia layanan pihak ketiga, untuk keperluan hukum, atau jika terjadi merger/akuisisi.</p>

<p>4. Cookies dan Teknologi Pelacakan</p>
<p>Kami menggunakan cookies untuk menyimpan preferensi Anda, mengingat keranjang belanja, memahami interaksi pengguna, dan memberikan iklan yang relevan. Anda dapat menonaktifkan cookies melalui pengaturan browser, tetapi beberapa fitur mungkin tidak berfungsi.</p>

<p>5. Hak Anda</p>
<p>Anda berhak mengakses, memperbarui, atau menghapus informasi pribadi Anda, serta menolak komunikasi promosi. Untuk melakukannya, hubungi kami di <a href="mailto:ahlinyaretail@gmail.com">ahlinyaretail@gmail.com</a>.</p>

<p>6. Keamanan Data</p>
<p>Kami menjaga keamanan informasi pribadi dengan langkah teknis dan organisasi. Namun, tidak ada layanan online yang sepenuhnya aman, jadi harap berhati-hati dalam membagikan informasi.</p>

<p>7. Tautan Pihak Ketiga</p>
<p>Layanan kami mungkin mengandung tautan ke situs atau aplikasi pihak ketiga. Kami tidak bertanggung jawab atas praktik privasi mereka.</p>

<p>8. Privasi Anak</p>
<p>Layanan kami tidak ditujukan untuk anak di bawah usia 13 tahun. Jika kami mengetahui telah mengumpulkan data anak, kami akan menghapusnya.</p>

<p>9. Pengguna Internasional</p>
<p>Jika Anda mengakses layanan kami dari luar Indonesia, data Anda mungkin dipindahkan ke negara lain. Dengan menggunakan layanan kami, Anda menyetujui hal ini.</p>

<p>10. Perubahan Kebijakan Privasi</p>
<p>Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Tanggal efektif akan diperbarui, dan kami akan memberitahu pengguna melalui layanan atau email.</p>
';


        $termsAndConditionsContent = '
<p>Selamat datang di Ahlinya Retail! Dengan menggunakan layanan kami, termasuk website, aplikasi mobile, dan produk fashion kami, Anda setuju untuk mematuhi Syarat & Ketentuan berikut. Mohon baca dengan seksama sebelum menggunakan layanan.</p>

<p>1. Penggunaan Layanan</p>
<p>Layanan ini hanya untuk individu yang berusia 13 tahun ke atas atau sesuai batas usia yang berlaku di negara Anda. Anda setuju untuk tidak menggunakan layanan untuk tujuan ilegal atau yang melanggar hak pihak lain.</p>

<p>2. Akun Pengguna</p>
<p>Beberapa fitur mungkin memerlukan pembuatan akun. Anda bertanggung jawab atas kerahasiaan akun dan password. Kami tidak bertanggung jawab atas kerugian akibat penggunaan akun oleh pihak lain.</p>

<p>3. Pesanan dan Pembayaran</p>
<p>- Semua pesanan tunduk pada ketersediaan stok.<br>
- Harga dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.<br>
- Pembayaran dilakukan melalui metode yang kami sediakan. Anda bertanggung jawab atas keakuratan informasi pembayaran.</p>

<p>4. Pengiriman</p>
<p>Kami akan mengirimkan pesanan sesuai alamat yang Anda berikan. Kami tidak bertanggung jawab atas kesalahan alamat atau keterlambatan yang disebabkan oleh jasa pengiriman pihak ketiga.</p>

<p>5. Pengembalian dan Refund</p>
<p>- Pengembalian dapat dilakukan sesuai kebijakan pengembalian kami.<br>
- Refund hanya berlaku jika produk dikembalikan sesuai syarat yang ditentukan.</p>

<p>6. Hak Kekayaan Intelektual</p>
<p>Seluruh konten, logo, gambar, teks, dan desain di layanan kami adalah milik Ahlinya Retail atau mitra kami dan dilindungi oleh hukum hak cipta dan merek dagang. Anda dilarang menyalin atau menggunakan konten tanpa izin tertulis.</p>

<p>7. Tanggung Jawab Pengguna</p>
<p>Anda setuju untuk menggunakan layanan secara bertanggung jawab. Kami tidak bertanggung jawab atas kerugian atau kerusakan yang timbul akibat penyalahgunaan layanan.</p>

<p>8. Perubahan Syarat & Ketentuan</p>
<p>Kami berhak memperbarui Syarat & Ketentuan ini dari waktu ke waktu. Perubahan akan berlaku efektif setelah diumumkan di layanan kami.</p>

<p>9. Hukum yang Berlaku</p>
<p>Syarat & Ketentuan ini diatur oleh hukum yang berlaku di Indonesia. Setiap sengketa akan diselesaikan secara musyawarah atau melalui pengadilan yang berwenang di Indonesia.</p>
';


        DB::table('information_pages')->insert([
            [
                'slug' => 'privacy-policy',
                'title' => 'Kebijakan Privasi',
                'content' => $privacyPolicyContent,
                'company_name' => null,
                'company_tagline' => null,
                'whatsapp' => null,
                'email' => null,
                'address' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'tiktok_url' => null,
                'youtube_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'slug' => 'terms-and-conditions',
                'title' => 'Syarat & Ketentuan',
                'content' => $termsAndConditionsContent,
                'company_name' => null,
                'company_tagline' => null,
                'whatsapp' => null,
                'email' => null,
                'address' => null,
                'facebook_url' => null,
                'instagram_url' => null,
                'tiktok_url' => null,
                'youtube_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'slug' => 'general-settings',
                'title' => 'Pengaturan Umum',
                'content' => null,
                'company_name' => 'Ahlinya Retail',
                'company_tagline' => 'Toko Pakaian Online Dengan Koleksi Terkini Dengan Kualitas Premium',
                'whatsapp' => '6281234567890',
                'email' => 'email@tokokami.com',
                'address' => 'Jalan Kenangan No. 123, Jakarta',
                'facebook_url' => 'https://facebook.com/tokokami',
                'instagram_url' => 'https://instagram.com/tokokami',
                'tiktok_url' => 'https://tiktok.com/@tokokami',
                'youtube_url' => 'https://youtube.com/tokokami',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
