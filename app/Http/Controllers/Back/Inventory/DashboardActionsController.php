<?php
// app/Http/Controllers/Back/Inventory/DashboardActionsController.php

namespace App\Http\Controllers\Back\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Cms\ProductCategory;
use App\Http\Requests\Inventory\StoreProductRequest;
use App\Http\Requests\Inventory\StockOpnameRequest;
use App\Services\Inventory\NewProductService;
use App\Services\Inventory\StockOpnameService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


/**
 * Controller ini mengelola aksi-aksi yang terkait dengan inventaris,
 * seperti menambah produk baru dan melakukan stock opname.
 * Menggunakan service layer untuk memisahkan logika bisnis dari controller.
 */
class DashboardActionsController extends Controller
{
    protected NewProductService $newProductService;
    protected StockOpnameService $stockOpnameService;

    /**
     * Konstruktor untuk menginjeksi service yang dibutuhkan.
     * @param NewProductService $newProductService
     * @param StockOpnameService $stockOpnameService
     */
    public function __construct(NewProductService $newProductService, StockOpnameService $stockOpnameService)
    {
        $this->newProductService = $newProductService;
        $this->stockOpnameService = $stockOpnameService;
    }

    /**
     * Menampilkan form untuk menambah produk baru.
     * Mengambil semua kategori produk untuk ditampilkan di dropdown.
     * @return View
     */
    public function createProduct(): View
    {
        $categories = ProductCategory::orderBy('name')->get();
        return view('back.inventory.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     * Meneruskan request ke service untuk validasi dan penyimpanan.
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function storeProduct(StoreProductRequest $request): RedirectResponse
    {
        // Controller hanya perlu memanggil service dan meneruskan objek request.
        $this->newProductService->store($request);
        return redirect()->route('admin.inventory.products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman untuk melakukan stock opname.
     * Mengambil daftar produk yang ada untuk diisi stok fisiknya.
     * @return View
     */
    public function showStockOpname(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->orderBy('name')->get();
        $categories = ProductCategory::orderBy('name')->get();

        return view('back.inventory.opname.index', compact('products', 'categories'));
    }

    /**
     * Menyimpan data stock opname dari form.
     * Meneruskan data ke service untuk memproses mutasi stok.
     * @param StockOpnameRequest $request
     * @return RedirectResponse
     */
    public function storeStockOpname(StockOpnameRequest $request): RedirectResponse
    {
        $this->stockOpnameService->storeOpname($request->opname_data);
        return redirect()->route('admin.inventory.products.index')->with('success', 'Stock opname berhasil disimpan.');
    }
}
