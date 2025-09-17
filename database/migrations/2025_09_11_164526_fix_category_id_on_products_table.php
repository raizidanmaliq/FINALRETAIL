<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Pastikan nullable
            $table->unsignedBigInteger('category_id')->nullable()->change();

            // Hapus foreign key lama (yang cascade)
            $table->dropForeign(['category_id']);

            // Tambahkan foreign key baru dengan nullOnDelete
            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus foreign key set null
            $table->dropForeign(['category_id']);

            // Tambahkan lagi foreign key dengan cascade
            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->cascadeOnDelete();
        });
    }
};
