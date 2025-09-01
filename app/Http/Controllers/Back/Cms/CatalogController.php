<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Services\Cms\CatalogService;
use App\Http\Requests\Cms\ManageProductRequest;
use Illuminate\Http\Request;
use App\Helpers\ImageHelpers; // Ditambahkan: Walaupun tidak digunakan, ini untuk konsistensi.

class CatalogController extends Controller
{
    protected $cmsCatalogService;

    public function __construct(CatalogService $cmsCatalogService)
    {
        $this->cmsCatalogService = $cmsCatalogService;
    }

    /**
     * Menampilkan halaman CMS untuk mengelola produk yang akan ditampilkan.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('back.cms.catalog.index');
    }

    /**
     * Mengambil data produk untuk Datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        return $this->cmsCatalogService->data($request);
    }

    /**
     * Mengelola status tampil produk di katalog.
     *
     * @param  \App\Http\Requests\Cms\ManageProductRequest  $request
     * @param  \App\Models\Inventory\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleDisplay(ManageProductRequest $request, Product $product)
    {
        $action = $request->input('action');
        $isDisplayed = ($action === 'show');

        $this->cmsCatalogService->updateDisplayStatus($product, $isDisplayed);

        return response()->json(['message' => 'Status produk berhasil diperbarui.'], 200);
    }
}
