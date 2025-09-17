<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialsTable extends Migration
{
    public function up()
    {
        Schema::create('socials', function (Blueprint $table) {
            $table->id();
            $table->json('images');
            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();
            $table->string('shopee_link')->nullable();
            $table->string('tokopedia_link')->nullable();
            $table->string('lazada_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('socials');
    }
}
