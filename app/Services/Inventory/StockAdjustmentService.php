<?php

namespace App\Services\Inventory;

use App\Models\Inventory\Product;
use App\Models\Inventory\StockMutation;
use Illuminate\Support\Facades\DB;
use App\Helpers\ErrorHandling;

class StockAdjustmentService
{
    /**
     * Tambah stok produk
     */
    public function addStock(Product $product, int $quantity, ?string $note = null, ?float $new_cost_price = null): void
    {
        try {
            DB::beginTransaction();

            $product->increment('stock', $quantity);

            // Update cost price if provided
            if (!is_null($new_cost_price)) {
                $product->update(['cost_price' => $new_cost_price]);
            }

            StockMutation::create([
                'product_id' => $product->id,
                'user_id'    => auth()->id(),
                'type'       => 'in',
                'quantity'   => $quantity,
                'note'       => $note ?? 'Penambahan stok',
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    /**
     * Koreksi stok produk
     */
    public function correctStock(Product $product, int $newStock, ?string $note = null): void
    {
        try {
            DB::beginTransaction();

            $oldStock   = $product->stock;
            $difference = $newStock - $oldStock;

            $product->update(['stock' => $newStock]);

            if ($difference !== 0) {
                StockMutation::create([
                    'product_id' => $product->id,
                    'user_id'    => auth()->id(),
                    'type'       => ($difference > 0) ? 'in' : 'out',
                    'quantity'   => abs($difference),
                    'note'       => $note ?? 'Koreksi stok',
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }
}
