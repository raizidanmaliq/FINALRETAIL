<?php
// app/Http/Controllers/Back/Inventory/CashflowController.php

namespace App\Http\Controllers\Back\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PurchaseOrder\PurchaseOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashflowController extends Controller
{
    public function index(Request $request)
    {
        // --- LOGIKA FILTER UNTUK SUMMARY BOX (TERPISAH) ---
        $summaryStartDate = $request->get('summary_start_date');
        $summaryEndDate = $request->get('summary_end_date');

        $summaryIncomeQuery = Order::where('order_status', 'completed');
        $summaryExpenseQuery = PurchaseOrder::where('status', 'completed')
            ->join('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
            ->join('products', 'purchase_order_details.product_id', '=', 'products.id');

        if ($summaryStartDate && $summaryEndDate) {
            $summaryStartDate = Carbon::parse($summaryStartDate);
            $summaryEndDate = Carbon::parse($summaryEndDate)->endOfDay();
            $summaryIncomeQuery->whereBetween('created_at', [$summaryStartDate, $summaryEndDate]);
            $summaryExpenseQuery->whereBetween('purchase_orders.created_at', [$summaryStartDate, $summaryEndDate]);
        }

        $totalIncome = $summaryIncomeQuery->sum('total_price');
        $totalExpenses = $summaryExpenseQuery->selectRaw('SUM(purchase_order_details.quantity * products.cost_price) as total_cost')->first()->total_cost ?? 0;
        $netCashFlow = $totalIncome - $totalExpenses;


        // --- LOGIKA FILTER UNTUK GRAFIK (HANYA MINGGUAN & BULANAN) ---
        $chartPeriod = $request->get('chart_period', '7_days');

        $chartEndDate = Carbon::now()->endOfDay(); // pastikan ambil sampai akhir hari ini
$chartStartDate = match ($chartPeriod) {
    '30_days' => Carbon::now()->subDays(29)->startOfDay(), // 30 hari termasuk hari ini
    default   => Carbon::now()->subDays(6)->startOfDay(),  // 7 hari termasuk hari ini
};


        $chartData = [];
        $currentChartDate = clone $chartStartDate;
        while ($currentChartDate->lte($chartEndDate)) {
            $dateString = $currentChartDate->format('Y-m-d');
            $dailyIncome = Order::where('order_status', 'completed')->whereDate('created_at', $dateString)->sum('total_price');
            $dailyExpenses = PurchaseOrder::where('status', 'completed')->whereDate('purchase_orders.created_at', $dateString)
                ->join('purchase_order_details', 'purchase_orders.id', '=', 'purchase_order_details.purchase_order_id')
                ->join('products', 'purchase_order_details.product_id', '=', 'products.id')
                ->selectRaw('SUM(purchase_order_details.quantity * products.cost_price) as total_cost')->first()->total_cost ?? 0;
            $chartData[] = ['date' => $currentChartDate->format('d M'), 'income' => $dailyIncome, 'expenses' => $dailyExpenses, 'net' => $dailyIncome - $dailyExpenses];
            $currentChartDate->addDay();
        }

        // --- LOGIKA FILTER UNTUK TABEL (TERPISAH) ---
        $tableStartDate = $request->get('table_start_date');
        $tableEndDate = $request->get('table_end_date');

        $tableQuery = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('product_categories', 'products.category_id', '=', 'product_categories.id');

        if ($tableStartDate && $tableEndDate) {
            $tableQuery->whereBetween('orders.created_at', [Carbon::parse($tableStartDate), Carbon::parse($tableEndDate)->endOfDay()]);
        }

        $categorySummary = $tableQuery->select(
                \DB::raw('COALESCE(product_categories.name, "Tidak Ada Kategori") as category_name'),
                \DB::raw('COUNT(DISTINCT orders.id) as total_transactions'),
                \DB::raw('SUM(CASE WHEN orders.order_status = "pending" THEN 1 ELSE 0 END) as pending'),
                \DB::raw('SUM(CASE WHEN orders.order_status = "processing" THEN 1 ELSE 0 END) as processing'),
                \DB::raw('SUM(CASE WHEN orders.order_status = "shipped" THEN 1 ELSE 0 END) as shipped'),
                \DB::raw('SUM(CASE WHEN orders.order_status = "completed" THEN 1 ELSE 0 END) as completed'),
                \DB::raw('SUM(CASE WHEN orders.order_status = "cancelled" THEN 1 ELSE 0 END) as cancelled'),
                \DB::raw('SUM(CASE WHEN orders.order_status = "completed" THEN order_items.subtotal ELSE 0 END) as total_revenue')
            )
            ->groupBy('category_name')
            ->get();

        $data = [
            // Data untuk summary box
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'net_cash_flow' => $netCashFlow,
            'summary_start_date' => $summaryStartDate,
            'summary_end_date' => $summaryEndDate,

            // Data untuk grafik
            'chart_data' => $chartData,
            'chart_period' => $chartPeriod,
            // Perbaikan: Pastikan kunci ini selalu ada
            'chart_date_range' => [
                'start' => $chartStartDate->format('Y-m-d'),
                'end' => $chartEndDate->format('Y-m-d'),
            ],

            // Data untuk tabel
            'category_summary' => $categorySummary,
            'table_start_date' => $tableStartDate,
            'table_end_date' => $tableEndDate,
        ];

        return view('back.inventory.cashflow.index', compact('data'));
    }
}
