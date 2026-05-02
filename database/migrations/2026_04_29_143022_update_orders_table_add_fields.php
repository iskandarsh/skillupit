<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->unsignedBigInteger('kelas_id')->nullable()->after('no_hp');
            $table->string('nama_kelas')->nullable()->after('kelas_id');
            $table->integer('amount')->nullable()->after('nama_kelas');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['kelas_id', 'nama_kelas', 'amount']);
        });
    }
};
