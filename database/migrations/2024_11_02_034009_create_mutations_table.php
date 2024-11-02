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
        Schema::create('mutations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->date('date');
            $table->enum('type', ['in', 'out']);
            $table->integer('quantity');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            
            // Menambahkan foreign key constraints dengan restrict on delete
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutations');
    }
};
