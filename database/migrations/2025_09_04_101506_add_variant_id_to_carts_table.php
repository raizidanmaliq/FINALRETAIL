<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Tambah kolom variant_id
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');

            // Foreign key ke product_variants
            $table->foreign('variant_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('cascade');

            // Unique baru: (customer_id, product_id, variant_id)
            $table->unique(
                ['customer_id', 'product_id', 'variant_id'],
                'carts_customer_id_product_id_variant_id_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('carts_customer_id_product_id_variant_id_unique');
            $table->dropForeign(['variant_id']);
            $table->dropColumn('variant_id');
        });
    }
};
