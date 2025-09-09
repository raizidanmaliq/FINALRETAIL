<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Cms\Banner;
use App\Models\Cms\Testimonial;
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
            ->latest() // Mengambil produk terbaru
            ->take(2)
            ->get();

        // Mengambil banner yang aktif dan paling baru diperbarui
        $banner = Banner::where('is_active', true)
            ->latest('updated_at')
            ->first();

        // Mengambil testimoni dengan filter rating
        $query = Testimonial::latest();

        if ($request->has('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        $testimonials = $query->paginate(4);

        // Menghitung total review dan rata-rata rating
        $totalReviews = Testimonial::count();
        $averageRating = Testimonial::avg('rating');

        return view('front.home.index', compact(
            'bestSellerProducts',
            'flashSaleProducts',
            'banner',
            'testimonials',
            'totalReviews',
            'averageRating'
        ));
    }

    public function getProductDetails(Product $product)
    {
        $product->load(['variants', 'images']);
        return response()->json($product);
    }
}
