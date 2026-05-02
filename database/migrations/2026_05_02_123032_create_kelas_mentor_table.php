<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kelas_mentor', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelas_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mentor_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            // biar tidak duplicate
            $table->unique(['kelas_id', 'mentor_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_mentor');
    }
};
