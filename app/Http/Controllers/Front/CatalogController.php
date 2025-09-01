<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Cms\ProductCategory;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan semua kategori untuk dropdown filter
        $categories = ProductCategory::all();

        // Mengambil produk-produk dengan label promo tanpa filter
        // Mengambil produk-produk dengan label promo tanpa filter
$promoProducts = Product::where('is_displayed', true)
                               ->where('promo_label', 'Flash Sale') // Tambahkan filter ini
                               ->get();

        // Query dasar untuk semua produk yang ditampilkan
        $query = Product::where('is_displayed', true);

        // --- Logika Pencarian dan Filter ---

        // 1. Filter berdasarkan pencarian (search)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 2. Filter berdasarkan kategori
        if ($request->filled('category')) {
    $categoryName = $request->input('category');
    $query->whereHas('category', function ($q) use ($categoryName) {
        $q->where('name', $categoryName); // Sekarang memfilter berdasarkan 'name'
    });
}

        // 3. Filter berdasarkan harga
        if ($request->filled('price')) {
            $priceRange = $request->input('price');
            switch ($priceRange) {
                case 'under-100k':
                    $query->where('selling_price', '<', 100000);
                    break;
                case '100k-200k':
                    $query->whereBetween('selling_price', [100000, 200000]);
                    break;
                // Tambahkan case lain jika ada
            }
        }

        // 4. Urutkan (sort) produk
        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'newest':
                    $query->latest();
                    break;
                case 'price_asc':
                    $query->orderBy('selling_price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('selling_price', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            // Urutan default jika tidak ada parameter sort
            $query->latest();
        }

        // Ambil produk setelah semua filter dan pengurutan diterapkan
        $allProducts = $query->paginate(12);

        // Kirim data ke view
        return view('front.catalog.index', compact('allProducts', 'promoProducts', 'categories'));
    }
}
