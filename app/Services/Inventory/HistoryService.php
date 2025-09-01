<?php

namespace App\Services\Inventory; // Perbaikan: Sesuaikan dengan direktori

use App\Models\Inventory\StockMutation; // Sesuai dengan model di Inventory
use App\Helpers\ErrorHandling;

class HistoryService
{
    /**
     * Mengambil semua riwayat mutasi stok terbaru.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockMutations()
    {
        try {
            return StockMutation::with('product', 'user')->latest()->get();
        } catch (\Exception $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
            return collect();
        }
    }
}
