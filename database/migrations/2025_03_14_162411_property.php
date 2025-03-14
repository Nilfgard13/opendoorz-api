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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 15, 2);
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('area');
            $table->integer('floor');
            $table->string('address');
            $table->integer('parking')->nullable();
            $table->enum('status',['available', 'sold', 'reserved', 'on progress']);
            $table->foreignId('category_type_id')->constrained('category_types')->onDelete('cascade');
            $table->foreignId('category_location_id')->constrained('category_locations')->onDelete('cascade');
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
