<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;

class ProductDetailController extends Controller
{
    public function show(Product $product)
    {
        if (!$product || !$product->is_displayed) {
            abort(404);
        }

        $product->load('images', 'variants');
        $whatsappNumber = env('ADMIN_PHONE_NUMBER');

        // Mengganti spasi dengan tanda hubung (-) untuk URL yang lebih rapi
        $productNameForUrl = str_replace(' ', '-', $product->name);
        $productName = rawurlencode($product->name);

        $message = "Halo Ahlinya Retail, saya ingin menanyakan tentang produk *{$product->name}*?";
$whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . rawurlencode($message);


        return view('customer.products.show', compact('product', 'whatsappUrl'));
    }
}
