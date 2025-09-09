<?php

namespace App\Http\Controllers\Back\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Cms\ProductCategory;
use App\Services\Inventory\ProductService;
use App\Services\Inventory\StockAdjustmentService;
use App\Http\Requests\Inventory\UpdateProductMasterRequest;
use App\Http\Requests\Inventory\AddStockRequest;
use App\Http\Requests\Inventory\CorrectStockRequest;
use Illuminate\Http\Request;
use App\Helpers\ImageHelpers;
use Illuminate\Http\RedirectResponse; // Tambahkan ini

class ProductController extends Controller
{
    protected $productService;
    protected $stockAdjustmentService;

    public function __construct(ProductService $productService, StockAdjustmentService $stockAdjustmentService)
    {
        $this->productService = $productService;
        $this->stockAdjustmentService = $stockAdjustmentService;
    }

    public function index()
    {
        $categories = ProductCategory::all();
        return view('back.inventory.products.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk mengedit produk.
     * Mengambil data produk, kategori, varian, dan gambar.
     * @param Product $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::all();
        $genders = ['Pria', 'Wanita', 'Unisex'];

        // Ambil data varian dan gambar yang sudah ada
        $product->load('variants', 'images');

        // Untuk mengisi form, kelompokkan varian berdasarkan warna dan ukuran
        $colors = $product->variants->pluck('color')->unique()->values();
        $sizes = $product->variants->pluck('size')->unique()->values();

        return view('back.inventory.products.edit', compact('product', 'categories', 'genders', 'colors', 'sizes'));
    }

    /**
     * Memperbarui produk yang sudah ada.
     * Mengelola pembaruan data dasar, varian, dan gambar.
     * @param UpdateProductMasterRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(UpdateProductMasterRequest $request, Product $product): RedirectResponse
    {
        // Panggil service untuk menangani logika pembaruan
        $this->productService->updateProduct($request, $product);

        return redirect()->route('admin.inventory.products.index')
            ->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Handle quick stock addition from modal.
     */
    public function addStock(AddStockRequest $request, Product $product)
    {
        $this->stockAdjustmentService->addStock(
            $product,
            $request->quantity,
            'Penambahan stok melalui quick action',
            $request->new_cost_price
        );

        return response()->json(['message' => 'Stok berhasil ditambahkan.']);
    }

    /**
     * Handle quick stock correction from modal.
     */
    public function correctStock(CorrectStockRequest $request, Product $product)
    {
        $this->stockAdjustmentService->correctStock(
            $product,
            $request->stock,
            $request->note
        );

        return response()->json(['message' => 'Stok berhasil dikoreksi.']);
    }

    public function data(Request $request)
    {
        return $this->productService->data($request);
    }
}
