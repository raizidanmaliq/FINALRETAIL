<?php

namespace App\Services\Cms;

use App\Models\Order;
use App\Models\OrderItem; // Menggunakan OrderItem
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerOrderService
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'order_code' => 'ORD-' . Carbon::now()->format('YmdHis'),
                'total_price'      => (int) str_replace('.', '', $request->total_price),
                'shipping_address' => $request->shipping_address,
                'order_status' => 'pending',
            ]);

            foreach ($request->products as $product) {
                // Menggunakan model OrderItem
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['unit_price'],
                    'subtotal' => $product['quantity'] * $product['unit_price'],
                ]);
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat pesanan. ' . $e->getMessage());
        }
    }

    public function update(Order $order, Request $request)
    {
        DB::beginTransaction();
        try {
            $order->update([
                'customer_id' => $request->customer_id,
                'total_price'      => (int) str_replace('.', '', $request->total_price),
                'shipping_address' => $request->shipping_address,
                'order_status' => $request->order_status,
            ]);

            // Panggil relasi 'items'
            $order->items()->delete();

            foreach ($request->products as $product) {
                // Menggunakan model OrderItem
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['unit_price'],
                    'subtotal' => $product['quantity'] * $product['unit_price'],
                ]);
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui pesanan. ' . $e->getMessage());
        }
    }

    public function generateDataTables()
{
    $orders = Order::with(['customer'])
        ->select(['id', 'order_code', 'customer_id', 'total_price', 'order_status', 'created_at']);

    return DataTables::of($orders)
        ->addColumn('customer_name', function ($order) {
            return $order->customer->name ?? 'N/A';
        })
        ->editColumn('total_price', function ($order) {
            return number_format($order->total_price, 0, ',', '.');
        })
        ->editColumn('created_at', function ($order) {
            // Format contoh: 28-08-2025 14:49
            return \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i');
        })
        ->addColumn('actions', function ($order) {
            $editUrl = route('admin.cms.customer-orders.edit', $order);
            $showUrl = route('admin.cms.customer-orders.show', $order);
            $pdfUrl  = route('admin.cms.customer-orders.export-pdf', $order);

            return '
                <div class="btn-group">
                    <a href="' . $editUrl . '" class="btn btn-outline-warning"><i class="fa fa-pencil-alt"></i></a>
                    <a href="' . $showUrl . '" class="btn btn-outline-info show-order" data-id="'.$order->id.'"><i class="fa fa-eye"></i></a>
                    <a href="' . $pdfUrl . '" class="btn btn-outline-danger" target="_blank"><i class="fa fa-file-pdf"></i></a>
                </div>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);
}

}
