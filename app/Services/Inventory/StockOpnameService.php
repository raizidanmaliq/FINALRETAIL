<?php
// app/Services/Inventory/StockOpnameService.php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use Illuminate\Support\Facades\DB;
use App\Helpers\ErrorHandling;

/**
 * Service untuk menangani logika bisnis terkait stock opname.
 */
class StockOpnameService
{
    /**
     * Menyimpan data stock opname dan mencatat mutasi stok.
     * Menggunakan transaksi database untuk memastikan konsistensi.
     * @param array $opnameData
     */
    public function storeOpname(array $opnameData): void
    {
        try {
            DB::beginTransaction();

            foreach ($opnameData as $productId => $data) {
                $product = Product::find($productId);

                if ($product) {
                    $oldStock = $product->stock;

                    // Kalau tidak diisi, otomatis pakai stok lama
                    $newStock = isset($data['physical_stock']) && $data['physical_stock'] !== null
                        ? (int) $data['physical_stock']
                        : $oldStock;

                    $difference = $newStock - $oldStock;

                    // Update hanya kalau ada perbedaan stok
                    if ($difference != 0) {
                        $product->update(['stock' => $newStock]);

                        StockMutation::create([
                            'product_id' => $product->id,
                            'user_id' => auth()->id(), // user opname
                            'type' => ($difference > 0) ? 'in' : 'out',
                            'quantity' => abs($difference),
                            'note' => $data['note'] ?? 'Koreksi dari Stock Opname'
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }
}
