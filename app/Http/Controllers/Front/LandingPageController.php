<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Cms\Banner;
use App\Models\Cms\Testimonial;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    // Perbaikan: Tambahkan Request $request sebagai parameter
    public function index(Request $request)
    {
        $bestSellerProducts = Product::where('is_displayed', true)
                                     ->where('promo_label', 'Bestseller')
                                     ->latest()
                                     ->take(4)
                                     ->get();

        $flashSaleProducts = Product::where('is_displayed', true)
                                     ->where('promo_label', 'Flash Sale')
                                     ->take(2)
                                     ->get();

        $banner = Banner::where('is_active', true)
                        ->latest('updated_at')
                        ->first();

        // Mulai query untuk testimoni
        $query = Testimonial::latest();

        // Tambahkan kondisi filter jika parameter rating ada di URL
        if ($request->has('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Jalankan paginasi pada query yang sudah difilter
        $testimonials = $query->paginate(4);

        // Menghitung total ulasan untuk tombol "Semua"
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
}
