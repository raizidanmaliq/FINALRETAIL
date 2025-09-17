<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Menambahkan kolom 'is_video' dengan nilai default 'false'
            $table->boolean('is_video')->default(false)->after('image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Menghapus kolom 'is_video' jika migrasi di-rollback
            $table->dropColumn('is_video');
        });
    }
};
