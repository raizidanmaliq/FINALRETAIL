<?php

namespace App\Services\PurchaseOrder;

use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderDetail;
use App\Http\Requests\PurchaseOrder\PurchaseOrderRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseOrderService {

    public function store(PurchaseOrderRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::create([
                'supplier_name' => $request->supplier_name,
                'po_number' => 'PO-' . Carbon::now()->format('YmdHis'),
                'order_date' => $request->order_date,
                'arrival_estimate_date' => $request->arrival_estimate_date,
            ]);

            foreach ($request->products as $product) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                ]);
            }

            DB::commit();
            return $po;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat pemesanan. ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui data pemesanan.
     * @param PurchaseOrder $purchaseOrder
     * @param PurchaseOrderRequest $request
     * @return PurchaseOrder
     */
    public function update(PurchaseOrder $purchaseOrder, PurchaseOrderRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            // Perbarui data utama Purchase Order
            $purchaseOrder->update([
                'supplier_name' => $request->supplier_name,
                'order_date' => $request->order_date,
                'arrival_estimate_date' => $request->arrival_estimate_date,
                'status' => $request->status, // Tambahkan baris ini
            ]);

            // Hapus detail lama sebelum membuat yang baru
            $purchaseOrder->details()->delete();

            // Buat detail baru dari request
            foreach ($request->products as $product) {
                PurchaseOrderDetail::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'unit_price' => $product['unit_price'],
                ]);
            }

            DB::commit();
            return $purchaseOrder;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui pemesanan. ' . $e->getMessage());
        }
    }

    public function generatePDF(PurchaseOrder $purchaseOrder)
    {
        $data = [
            'po' => $purchaseOrder->load(['details.product']),
        ];

        $pdf = Pdf::loadView('back.purchase-orders.pdf', $data);
        return $pdf->download('PO-' . $purchaseOrder->po_number . '.pdf');
    }

    public function generateDataTables()
    {
        $query = PurchaseOrder::with('details.product');

        // Filter by date range
        if (request()->has('from_date') && request()->has('to_date') && !empty(request('from_date'))) {
            $query->whereBetween('order_date', [request('from_date'), request('to_date')]);
        }

        // Filter by supplier name
        if (request()->has('supplier') && !empty(request('supplier'))) {
            $query->where('supplier_name', request('supplier'));
        }

        // Use the query builder directly, without calling ->get() here
        // The DataTables library will execute the query for you
        return DataTables::of($query)
            ->addColumn('actions', function ($po) {
                return '
                    <div class="btn-group">
                        <a href="' . route('admin.purchase-orders.edit', $po) . '" class="btn btn-outline-warning"><i class="fa fa-pencil-alt"></i></a>
                        <a href="#" class="btn btn-outline-info show-po" data-id="' . $po->id . '"><i class="fa fa-eye"></i></a>
                        <a href="' . route('admin.purchase-orders.pdf', $po) . '" class="btn btn-outline-secondary"><i class="fa fa-print"></i></a>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
