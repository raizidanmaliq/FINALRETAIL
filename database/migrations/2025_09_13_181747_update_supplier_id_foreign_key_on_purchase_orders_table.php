<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Hapus foreign key yang lama
            $table->dropForeign(['supplier_id']);

            // Tambahkan kembali foreign key dengan opsi ON DELETE SET NULL
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
        });
    }

    /**
     * Kembalikan migrasi.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Hapus foreign key yang baru
            $table->dropForeign(['supplier_id']);

            // Tambahkan kembali foreign key tanpa opsi ON DELETE
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });
    }
};
