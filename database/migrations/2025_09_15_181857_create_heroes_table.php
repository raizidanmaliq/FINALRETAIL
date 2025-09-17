<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeroesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline')->nullable();
            $table->string('subheadline')->nullable();
            $table->json('images')->nullable(); // Changed to a JSON column
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('heroes');
    }
}
