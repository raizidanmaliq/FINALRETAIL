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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method'); // transfer, e-wallet, dll
            $table->string('payer_name')->nullable(); // nama pembayar
            $table->decimal('amount', 15, 2);
            $table->timestamp('payment_date')->nullable(); // tanggal pembayaran
            $table->string('proof')->nullable(); // upload bukti transfer
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null'); // admin/verifikator
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
