<?php

namespace App\Services\Cms;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CustomerOrderService
{
    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        // Panggil validasi khusus untuk metode store
        $this->validateStoreRequest($request);

        DB::beginTransaction();
        try {
            // Langsung ambil customer_id dari request yang sekarang wajib ada
            $customerId = $request->customer_id;

            $totalPrice = $this->calculateTotalPrice($request->products);

            $order = Order::create([
                'customer_id' => $customerId, // Akan selalu memiliki nilai karena wajib diisi
                'order_code' => 'ORD-' . Carbon::now()->format('YmdHis') . '-' . rand(100, 999),
                'total_price' => $totalPrice,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email,
                'receiver_address' => $request->receiver_address,
                'order_status' => 'pending',
            ]);

            foreach ($request->products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['unit_price'],
                    'subtotal' => $product['quantity'] * $product['unit_price'],
                    'color' => $product['color'] ?? null,
                    'size' => $product['size'] ?? null,
                ]);
            }

            // Tangani data pembayaran opsional
            if ($request->filled(['payment_method', 'payment_date'])) {
                $paymentData = [
                    'amount' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'payment_date' => $request->payment_date,
                    'proof' => null, // default to null
                ];

                if ($request->hasFile('proof_of_payment')) {
                    $storagePath = 'back_assets/img/cms/payments/';
                    $proofPath = $request->file('proof_of_payment')->move(
                        public_path($storagePath),
                        $request->file('proof_of_payment')->hashName()
                    );
                    $paymentData['proof'] = $storagePath . basename($proofPath);
                }

                $order->payment()->create($paymentData);
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal membuat pesanan. ' . $e->getMessage());
        }
    }

    /**
     * Update an existing order.
     */
    public function update(Order $order, Request $request)
    {
        // Panggil validasi khusus untuk metode update
        $this->validateUpdateRequest($request);

        DB::beginTransaction();
        try {
            $totalPrice = $this->calculateTotalPrice($request->products);

            $order->update([
                'customer_id' => $request->customer_id,
                'total_price' => $totalPrice,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email,
                'receiver_address' => $request->receiver_address,
                'order_status' => $request->order_status,
            ]);

            $order->items()->delete();
            foreach ($request->products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['unit_price'],
                    'subtotal' => $product['quantity'] * $product['unit_price'],
                    'color' => $product['color'] ?? null,
                    'size' => $product['size'] ?? null,
                ]);
            }

            // Tangani data pembayaran
            if ($request->filled(['payment_method', 'payment_date'])) {
                $paymentData = [
                    'amount' => $totalPrice,
                    'payment_method' => $request->payment_method,
                    'payment_date' => $request->payment_date,
                ];

                if ($request->hasFile('proof_of_payment')) {
                    $storagePath = 'back_assets/img/cms/payments/';
                    $proofPath = $request->file('proof_of_payment')->move(
                        public_path($storagePath),
                        $request->file('proof_of_payment')->hashName()
                    );
                    $paymentData['proof'] = $storagePath . basename($proofPath);
                }

                if ($order->payment) {
                    $order->payment->update($paymentData);
                } else {
                    $order->payment()->create($paymentData);
                }
            }

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Gagal memperbarui pesanan. ' . $e->getMessage());
        }
    }

    /**
     * Validation rules for storing a new order.
     */
    private function validateStoreRequest(Request $request)
    {
        $rules = [
            'customer_id' => 'nullable|exists:customers,id', // Diubah menjadi wajib
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'required|email|max:255',
            'receiver_address' => 'required|string|max:500',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.color' => 'nullable|string|max:50',
            'products.*.size' => 'nullable|string|max:50',
            'payment_date' => 'nullable|date|before_or_equal:today',
            'payment_method' => 'nullable|string',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Exception(implode(', ', $validator->errors()->all()));
        }
    }

    /**
     * Validation rules for updating an existing order.
     */
    private function validateUpdateRequest(Request $request)
    {
        $rules = [
            'customer_id' => 'nullable|exists:customers,id',
            'receiver_name' => 'required|string|max:100',
            'receiver_phone' => 'required|string|max:20',
            'receiver_email' => 'required|email|max:255',
            'receiver_address' => 'required|string|max:500',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
            'products.*.color' => 'nullable|string|max:50',
            'products.*.size' => 'nullable|string|max:50',
            'order_status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'payment_date' => 'nullable|date|before_or_equal:today',
            'payment_method' => 'nullable|string',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new \Exception(implode(', ', $validator->errors()->all()));
        }
    }

    private function calculateTotalPrice(array $products)
    {
        return collect($products)->sum(function ($product) {
            return $product['quantity'] * $product['unit_price'];
        });
    }

    public function updateStatus(Order $order, $status)
    {
        try {
            $order->update(['order_status' => $status]);
        } catch (\Exception $e) {
            throw new \Exception('Gagal memperbarui status pesanan: ' . $e->getMessage());
        }
    }

    public function generateDataTables()
    {
        $orders = Order::with(['customer'])
            ->select(['id', 'order_code', 'receiver_name', 'total_price', 'order_status', 'created_at']);

        return DataTables::of($orders)
            ->addColumn('customer_name', function ($order) {
                return $order->customer->name ?? 'N/A';
            })
            ->editColumn('total_price', function ($order) {
                return number_format($order->total_price, 0, ',', '.');
            })
            ->editColumn('created_at', function ($order) {
                return \Carbon\Carbon::parse($order->created_at)->format('d-m-Y H:i');
            })
            ->addColumn('actions', function ($order) {
                $editUrl = route('admin.cms.customer-orders.edit', $order);
                $showUrl = route('admin.cms.customer-orders.show', $order);
                $pdfUrl  = route('admin.cms.customer-orders.export-pdf', $order);

                return '
                    <div class="btn-group">
                        <a href="' . $editUrl . '" class="btn btn-outline-warning"><i class="fa fa-pencil-alt"></i></a>
                        <a href="' . $showUrl . '" class="btn btn-outline-info show-order" data-id="' . $order->id . '"><i class="fa fa-eye"></i></a>
                        <a href="' . $pdfUrl . '" class="btn btn-outline-danger" target="_blank"><i class="fa fa-file-pdf"></i></a>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
