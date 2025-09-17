<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Cms\Banner;
use App\Models\Cms\Testimonial;
use App\Models\Cms\Hero;
use App\Models\Cms\Cta;
use App\Models\Cms\Social; // 🔹 Tambahkan model Social di sini
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil 4 produk bestseller terbaru dengan eager loading
        $bestSellerProducts = Product::where('is_displayed', true)
            ->where('promo_label', 'Bestseller')
            ->with(['images', 'variants'])
            ->latest()
            ->take(4)
            ->get();

        // Mengambil 2 produk flash sale terbaru dengan eager loading
        $flashSaleProducts = Product::where('is_displayed', true)
            ->where('promo_label', 'Flash Sale')
            ->with(['images', 'variants'])
            ->latest()
            ->take(2)
            ->get();

        // Mengambil semua banner yang aktif
        $banners = Banner::where('is_active', true)->get();

        // Mengambil testimoni dengan filter rating
        $query = Testimonial::latest();

        if ($request->has('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        $testimonials = $query->paginate(2);

        // Menghitung total review dan rata-rata rating
        $totalReviews = Testimonial::count();
        $averageRating = Testimonial::avg('rating');

        // Mengambil hero yang sedang aktif dari database
        $hero = Hero::where('is_active', true)->first();

        // Mengambil CTA yang sedang aktif dari database
        $cta = Cta::where('is_active', true)->first();

        // 🔹 Ambil entri Sosial & E-commerce yang aktif dari database
        $social = Social::where('is_active', true)->first();

        return view('front.home.index', compact(
            'bestSellerProducts',
            'flashSaleProducts',
            'banners',
            'testimonials',
            'totalReviews',
            'averageRating',
            'hero',
            'cta',
            'social' // 🔹 Tambahkan 'social' ke dalam compact
        ));
    }

    // 🔹 Tambahkan method ini agar modal bisa ambil data JSON produk
    public function getProductDetails(Product $product)
    {
        $product->load(['variants', 'images']);
        return response()->json($product);
    }
}
