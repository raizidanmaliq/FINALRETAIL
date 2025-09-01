<?php

namespace App\Http\Controllers\Back\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\PurchaseOrderRequest; // Ubah nama request
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\Inventory\Product;
use App\Services\PurchaseOrder\PurchaseOrderService;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    protected $purchaseOrderService;

    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
    }

    public function index()
    {
        return view('back.purchase-orders.index');
    }

    public function create()
    {
        $products = Product::all();
        return view('back.purchase-orders.create', compact('products'));
    }

    public function store(PurchaseOrderRequest $request) // Ubah nama request
    {
        try {
            $this->purchaseOrderService->store($request);
            return redirect()->route('admin.purchase-orders.index')->with('success', 'Pemesanan berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan halaman edit
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        // Load relasi details dan product untuk menampilkan data di form
        $purchaseOrder->load(['details.product']);
        $products = Product::all();
        return view('back.purchase-orders.edit', compact('purchaseOrder', 'products'));
    }

    /**
     * Proses update data pemesanan
     */
    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $this->purchaseOrderService->update($purchaseOrder, $request);
            return redirect()->route('admin.purchase-orders.index')->with('success', 'Pemesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['details.product']);
        return response()->json($purchaseOrder);
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $request->validate(['status' => 'required|in:pending,on_delivery,completed']);
        try {
            $this->purchaseOrderService->updateStatus($purchaseOrder, $request->status);
            return response()->json(['message' => 'Status berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function data(Request $request)
    {
        return $this->purchaseOrderService->generateDataTables();
    }

    public function exportPdf(PurchaseOrder $purchaseOrder)
    {
        return $this->purchaseOrderService->generatePDF($purchaseOrder);
    }
}
