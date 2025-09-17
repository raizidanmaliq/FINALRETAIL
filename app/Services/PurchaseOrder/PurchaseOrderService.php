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

    /**
     * Store a new purchase order and its details.
     *
     * @param PurchaseOrderRequest $request
     * @return PurchaseOrder
     * @throws \Exception
     */
    public function store(PurchaseOrderRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            $po = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
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
     * Update an existing purchase order and its details.
     *
     * @param PurchaseOrder $purchaseOrder
     * @param PurchaseOrderRequest $request
     * @return PurchaseOrder
     * @throws \Exception
     */
    public function update(PurchaseOrder $purchaseOrder, PurchaseOrderRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'arrival_estimate_date' => $request->arrival_estimate_date,
                'status' => $request->status,
            ]);

            $purchaseOrder->details()->delete();

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

    /**
     * Generate a PDF for the purchase order.
     *
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function generatePDF(PurchaseOrder $purchaseOrder)
    {
        $data = [
            'po' => $purchaseOrder->load(['supplier', 'details.product']),
        ];

        $pdf = Pdf::loadView('back.purchase-orders.pdf', $data);

        return $pdf->stream('PO-' . $purchaseOrder->id . '.pdf');
    }


    /**
     * Generate data for DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateDataTables()
    {
        $query = PurchaseOrder::with(['supplier', 'details.product']);

        if (request()->has('from_date') && request()->has('to_date') && !empty(request('from_date'))) {
            $query->whereBetween('order_date', [request('from_date'), request('to_date')]);
        }

        if (request()->has('supplier_id') && !empty(request('supplier_id'))) {
            $query->where('supplier_id', request('supplier_id'));
        }

        return DataTables::of($query)
            ->addColumn('supplier_name', function($po) {
                return $po->supplier ? $po->supplier->name : 'N/A';
            })
            ->addColumn('actions', function ($po) {
                return '
                    <div class="btn-group">
                        <a href="' . route('admin.purchase-orders.edit', $po) . '" class="btn btn-outline-warning"><i class="fa fa-pencil-alt"></i></a>
                        <a href="' . route('admin.purchase-orders.pdf', $po) . '" class="btn btn-outline-secondary"><i class="fa fa-print"></i></a>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
