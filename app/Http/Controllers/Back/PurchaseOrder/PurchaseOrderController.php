<?php

namespace App\Http\Controllers\Back\PurchaseOrder;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\PurchaseOrderRequest;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\Inventory\Product;
use App\Models\Supplier;
use App\Services\PurchaseOrder\PurchaseOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderController extends Controller
{
    protected $purchaseOrderService;

    public function __construct(PurchaseOrderService $purchaseOrderService)
    {
        $this->purchaseOrderService = $purchaseOrderService;
    }

    public function index()
    {
        $suppliers = Supplier::all();
        return view('back.purchase-orders.index', compact('suppliers'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('back.purchase-orders.create', compact('products', 'suppliers'));
    }

    public function store(PurchaseOrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->purchaseOrderService->store($request);
            DB::commit();
            return redirect()->route('admin.purchase-orders.index')->with('success', 'Pemesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan halaman edit
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['details.product', 'supplier']);
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('back.purchase-orders.edit', compact('purchaseOrder', 'products', 'suppliers'));
    }

    /**
     * Proses update data pemesanan
     */
    public function update(PurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            DB::beginTransaction();
            $this->purchaseOrderService->update($purchaseOrder, $request);
            DB::commit();
            return redirect()->route('admin.purchase-orders.index')->with('success', 'Pemesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['details.product', 'supplier']);
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
