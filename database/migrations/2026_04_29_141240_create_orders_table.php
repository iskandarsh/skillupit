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
        Schema::create('orders', function ($table) {
            $table->id();
            $table->string('nama');
            $table->string('email');
            $table->string('no_hp');
            $table->string('status')->default('pending');
            $table->string('invoice_id')->nullable();
            $table->text('payment_url')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
