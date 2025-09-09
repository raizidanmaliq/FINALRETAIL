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


class DashboardActionsController extends Controller
{
    protected NewProductService $newProductService;
    protected StockOpnameService $stockOpnameService;

    public function __construct(NewProductService $newProductService, StockOpnameService $stockOpnameService)
    {
        $this->newProductService = $newProductService;
        $this->stockOpnameService = $stockOpnameService;
    }

    /**
     * Menampilkan form untuk menambah produk baru.
     * Mengambil semua kategori produk dan pilihan gender.
     * @return View
     */
    public function createProduct(): View
    {
        $categories = ProductCategory::orderBy('name')->get();
        $genders = ['Pria', 'Wanita', 'Unisex'];
        return view('back.inventory.products.create', compact('categories', 'genders'));
    }

    /**
     * Menyimpan produk baru ke database, termasuk varian dan gambar.
     * @param StoreProductRequest $request
     * @return RedirectResponse
     */
    public function storeProduct(StoreProductRequest $request): RedirectResponse
    {
        $this->newProductService->store($request);
        return redirect()->route('admin.inventory.products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

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

    public function storeStockOpname(StockOpnameRequest $request): RedirectResponse
    {
        $this->stockOpnameService->storeOpname($request->opname_data);
        return redirect()->route('admin.inventory.products.index')->with('success', 'Stock opname berhasil disimpan.');
    }
}
