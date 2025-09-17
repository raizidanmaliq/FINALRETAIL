<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // pastikan kolom nullable
            $table->unsignedBigInteger('category_id')->nullable()->change();

            // hapus foreign key lama
            $table->dropForeign(['category_id']);

            // bikin foreign key baru → kalau category dihapus, category_id jadi NULL
            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);

            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->cascadeOnDelete(); // balikin ke semula
        });
    }
};
