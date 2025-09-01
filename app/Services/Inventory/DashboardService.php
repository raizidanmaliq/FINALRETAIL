<?php
// app/Services/Inventory/DashboardService.php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Helpers\ErrorHandling; // Asumsi helper ini sudah ada
use Exception;

class DashboardService
{
    /**
     * @var int Batas ambang untuk menentukan produk dengan stok menipis.
     */
    protected int $lowStockThreshold = 10;

    /**
     * Mengumpulkan semua data yang dibutuhkan untuk ditampilkan di dashboard.
     *
     * @return array
     */
    public function getDashboardData(): array
    {
        try {
            // Menghitung total stok dari semua produk
            $totalStock = Product::sum('stock');

            // Menghitung total jumlah produk yang ada di database
            $totalProducts = Product::count();

            // Menghitung jumlah produk dengan stok menipis (stok > 0 dan <= ambang batas)
            $lowStockCount = Product::where('stock', '>', 0)
                                    ->where('stock', '<=', $this->lowStockThreshold)
                                    ->count();

            // Menghitung jumlah produk yang stoknya habis (stok = 0)
            $outOfStockCount = Product::where('stock', 0)->count();

            // Mengambil data pergerakan stok (masuk dan keluar) selama 7 hari terakhir
            $stockMovementData = StockMutation::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
                DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

            // Mengisi data grafik dengan tanggal yang kosong untuk memastikan 7 hari lengkap
            $dateRange = collect(range(0, 6))->map(function($day) {
                return now()->subDays(6 - $day)->toDateString();
            });

            // Memetakan data pergerakan stok ke rentang tanggal yang lengkap
            $stockMovementData = $dateRange->map(function($date) use ($stockMovementData) {
                $found = $stockMovementData->firstWhere('date', $date);
                return [
                    'date' => $date,
                    'stock_in' => $found ? $found->stock_in : 0,
                    'stock_out' => $found ? $found->stock_out : 0,
                ];
            });

            // Mengembalikan semua data yang telah dihitung dalam bentuk array
            return [
                'total_stock' => $totalStock,
                'total_products' => $totalProducts, // Data jumlah produk baru
                'low_stock_count' => $lowStockCount,
                'out_of_stock_count' => $outOfStockCount,
                'stock_movement_data' => $stockMovementData
            ];
        } catch (Exception $e) {
            // Menggunakan ErrorHandling helper untuk mencatat error dan mengembalikan nilai default
            ErrorHandling::environmentErrorHandling($e->getMessage());

            return [
                'total_stock' => 0,
                'total_products' => 0, // Nilai default jika terjadi error
                'low_stock_count' => 0,
                'out_of_stock_count' => 0,
                'stock_movement_data' => collect([])
            ];
        }
    }
}
