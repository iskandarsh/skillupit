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
        Schema::create('moduls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id');

            $table->string('original_name'); // nama asli file
            $table->string('file_path');     // path file
            $table->bigInteger('file_size')->nullable(); // ukuran file

            $table->timestamps();

            // relasi ke tabel kelas (optional tapi recommended)
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moduls');
    }
};
