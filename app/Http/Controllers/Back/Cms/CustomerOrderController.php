<?php

namespace App\Http\Controllers\Back\Cms;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Cms\CustomerOrderService;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    protected $customerOrderService;

    public function __construct(CustomerOrderService $customerOrderService)
    {
        $this->customerOrderService = $customerOrderService;
    }

    public function index()
    {
        return view('back.cms.customer-orders.index');
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('variants')->get();
        return view('back.cms.customer-orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $this->customerOrderService->store($request);
            return redirect()->route('admin.cms.customer-orders.index')->with('success', 'Pesanan pelanggan berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.product', 'payment']);
        return view('back.cms.customer-orders.show', compact('order'));
    }

    public function edit(Order $customer_order)
    {
        // Variabel yang dikirimkan ke view harus sama dengan parameter metode
        $customer_order->load(['items.product', 'payment']);
        $customers = Customer::all();
        $products = Product::with('variants')->get();
        // Mengubah nama variabel dari $order menjadi $customer_order di compact
        return view('back.cms.customer-orders.edit', compact('customer_order', 'customers', 'products'));
    }

    public function update(Request $request, Order $customer_order)
    {
        try {
            $this->customerOrderService->update($customer_order, $request);
            return redirect()->route('admin.cms.customer-orders.index')->with('success', 'Pesanan pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,completed,cancelled']);
        try {
            $this->customerOrderService->updateStatus($order, $request->status);
            return response()->json(['message' => 'Status berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function data()
    {
        return $this->customerOrderService->generateDataTables();
    }

   public function exportPdf(Order $order)
{
    // Ambil data order dan relasinya
    $order->load(['customer', 'items.product']);

    // Buat PDF dari view
    $pdf = Pdf::loadView('back.cms.customer-orders.pdf', compact('order'));

    // Ganti .download() dengan .stream()
    // 'pesanan-' . $order->order_code . '.pdf' adalah nama file yang akan ditampilkan di tab browser
    return $pdf->stream('pesanan-' . $order->order_code . '.pdf');
}

    /**
     * Get product variants (colors and sizes) by product ID.
     *
     * @param \App\Models\Inventory\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductVariants(Product $product)
    {
        $colors = $product->variants->pluck('color')->unique()->values();
        $sizes = $product->variants->pluck('size')->unique()->values();

        return response()->json([
            'colors' => $colors,
            'sizes' => $sizes,
        ]);
    }
}
