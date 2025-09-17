<?php

namespace App\Services\Cms;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Inventory\Product;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Pastikan ini ada
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CustomerOrderService
{
    /**
     * Store a newly created order.
     */
    public function store(Request $request)
    {
        $this->validateStoreRequest($request);

        DB::beginTransaction();
        try {
            $totalPrice = $this->calculateTotalPrice($request->products);

            $order = Order::create([
                'customer_id' => $request->customer_id,
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

            $this->handlePayment($request, $order, $totalPrice);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update an existing order.
     */
    public function update(Order $order, Request $request)
    {
        $this->validateUpdateRequest($request, $order);

        DB::beginTransaction();
        try {
            $totalPrice = $this->calculateTotalPrice($request->products);
            $newStatus = $request->order_status;
            $oldStatus = $order->order_status;

            // Panggil fungsi privat untuk mengurangi stok
            if ($newStatus === 'completed' && $oldStatus !== 'completed') {
                $this->_decreaseStock($order);
            }

            $order->update([
                'customer_id' => $request->customer_id,
                'total_price' => $totalPrice,
                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_email' => $request->receiver_email,
                'receiver_address' => $request->receiver_address,
                'order_status' => $newStatus,
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

            $this->handlePayment($request, $order, $totalPrice);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Store or update payment data based on the request.
     *
     * @param Request $request
     * @param Order $order
     * @param float $totalPrice
     * @return void
     */
    private function handlePayment(Request $request, Order $order, $totalPrice)
    {
        if ($request->hasFile('proof_of_payment') || $request->filled('payment_method') || $request->filled('payment_date')) {
            $paymentData = [
                'amount' => $totalPrice,
                'payment_method' => $request->payment_method ?? null,
                'payment_date' => $request->payment_date ?? null,
                'proof' => null,
            ];

            if ($request->hasFile('proof_of_payment')) {
                $storagePath = 'back_assets/img/cms/payments/';
                $fileName = $request->file('proof_of_payment')->hashName();

                if (!is_dir(public_path($storagePath))) {
                    mkdir(public_path($storagePath), 0777, true);
                }

                $request->file('proof_of_payment')->move(public_path($storagePath), $fileName);
                $paymentData['proof'] = $storagePath . $fileName;

                if ($order->payment && $order->payment->proof) {
                    @unlink(public_path($order->payment->proof));
                }
            } else {
                $paymentData['proof'] = $order->payment->proof ?? null;
            }

            if ($order->payment) {
                $order->payment->update($paymentData);
            } else {
                $order->payment()->create($paymentData);
            }
        } else {
            if ($order->payment) {
                if ($order->payment->proof) {
                    @unlink(public_path($order->payment->proof));
                }
                $order->payment->delete();
            }
        }
    }

    /**
     * Validation rules for storing a new order.
     */
    private function validateStoreRequest(Request $request)
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
            'total_price' => 'required|numeric|min:1',
            'payment_date' => 'nullable|date|before_or_equal:today',
            'payment_method' => 'nullable|string|in:bank_transfer,ewallet,manual',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'payment_date.before_or_equal' => 'Tanggal pembayaran tidak boleh di masa depan.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            $isAnyPaymentFilled = $request->filled('payment_date') || $request->filled('payment_method') || $request->hasFile('proof_of_payment');
            $isAllPaymentFilled = $request->filled('payment_date') && $request->filled('payment_method') && $request->hasFile('proof_of_payment');

            if ($isAnyPaymentFilled && !$isAllPaymentFilled) {
                $validator->errors()->add('payment', 'Detail pembayaran harus diisi lengkap (Tanggal, Metode, dan Bukti Pembayaran).');
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Validation rules for updating an existing order.
     */
    private function validateUpdateRequest(Request $request, Order $order)
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
            'payment_method' => 'nullable|string|in:bank_transfer,ewallet,manual',
            'proof_of_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        $messages = [
            'payment_date.before_or_equal' => 'Tanggal pembayaran tidak boleh di masa depan.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request, $order) {
            $hasPaymentDate = $request->filled('payment_date');
            $hasPaymentMethod = $request->filled('payment_method');
            $hasNewProof = $request->hasFile('proof_of_payment');
            $hasExistingProof = $order->payment && $order->payment->proof;

            // Cek jika ada salah satu field pembayaran yang diisi
            $isAnyPaymentFilled = $hasPaymentDate || $hasPaymentMethod || $hasNewProof;

            // Kondisi valid: semua field diisi, atau semua field kosong
            $isAllPaymentValid = ($hasPaymentDate && $hasPaymentMethod && ($hasNewProof || $hasExistingProof)) || (!$hasPaymentDate && !$hasPaymentMethod && !$hasNewProof && !$hasExistingProof);

            // Jika ada salah satu field diisi, tapi tidak lengkap, maka tambahkan error
            if ($isAnyPaymentFilled && !$isAllPaymentValid) {
                $validator->errors()->add('payment', 'Detail pembayaran harus diisi lengkap (Tanggal, Metode, dan Bukti Pembayaran).');
            }

            // Jika semua field input dikosongkan, tapi ada data pembayaran lama yang ingin dihapus, validasi berhasil
            if (!$isAnyPaymentFilled && $hasExistingProof && !$request->input('payment_date') && !$request->input('payment_method')) {
                // Lulus validasi
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function calculateTotalPrice(array $products)
    {
        return collect($products)->sum(function ($product) {
            return $product['quantity'] * $product['unit_price'];
        });
    }

    /**
     * Update order status and decrease product stock if status is 'completed'.
     */
    public function updateStatus(Order $order, $status)
    {
        DB::beginTransaction();
        try {
            $oldStatus = $order->order_status;

            // Panggil fungsi privat untuk mengurangi stok
            if ($status === 'completed' && $oldStatus !== 'completed') {
                $this->_decreaseStock($order);
            }

            $order->update(['order_status' => $status]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Logika untuk mengurangi stok produk dari pesanan.
     * Metode ini akan dipanggil oleh metode lain.
     */
    private function _decreaseStock(Order $order)
    {
        Log::info('Memulai pengurangan stok untuk pesanan ID: ' . $order->id);
        $order->load('items.product');

        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $newStock = $product->stock - $item->quantity;
                $product->stock = max(0, $newStock);
                $product->save();
                Log::info("Stok produk '{$product->name}' (ID: {$product->id}) dikurangi. Stok baru: {$product->stock}");
            } else {
                Log::warning("Produk tidak ditemukan untuk item pesanan ID: {$item->id}");
            }
        }
        Log::info('Pengurangan stok selesai.');
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
                $pdfUrl = route('admin.cms.customer-orders.export-pdf', $order);

                return '
                    <div class="btn-group">
                        <a href="' . $editUrl . '" class="btn btn-outline-warning"><i class="fa fa-pencil-alt"></i></a>
                        <a href="' . $showUrl . '" class="btn btn-outline-info show-order" data-id="' . $order->id . '"><i class="fa fa-eye"></i></a>
                        <a href="' . $pdfUrl . '" class="btn btn-outline-danger"><i class="fa fa-file-pdf"></i></a>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
