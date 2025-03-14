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
        Schema::create('landing_page', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->string('email')->nullable();
            $table->string('slogan')->nullable();
            $table->json('images')->nullable();
            $table->string('url')->nullable();
            $table->string('url_ig')->nullable();
            $table->json('thumbnails')->nullable();
            $table->string('experience')->nullable();
            $table->string('gmap')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page');
    }
};
