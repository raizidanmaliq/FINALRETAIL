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
        $products = Product::all();
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
        // Memuat relasi yang diperlukan untuk tampilan detail
        $order->load(['customer', 'items.product', 'payment']);

        // Mengembalikan view Blade, bukan JSON
        return view('back.cms.customer-orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $order->load(['items.product']);
        $customers = Customer::all();
        $products = Product::all();
        return view('back.cms.customer-orders.edit', compact('order', 'customers', 'products'));
    }

    public function update(Request $request, Order $customer_order)
    {
        try {
            // Hapus baris di bawah ini karenaverified_by dan verified_at tidak ada di skema
            // $customer_order->verified_by = auth()->user()->id;
            // $customer_order->verified_at = now();

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
    // Muat relasi customer dan items.product
    $order->load(['customer', 'items.product']);

    // Tentukan view yang akan diubah menjadi PDF
    $pdf = Pdf::loadView('back.cms.customer-orders.pdf', compact('order'));

    // Unduh file PDF dengan nama yang relevan
    return $pdf->download('pesanan-' . $order->order_code . '.pdf');
}
}
