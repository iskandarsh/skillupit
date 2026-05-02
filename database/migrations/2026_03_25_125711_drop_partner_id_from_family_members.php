<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('family_members', function (Blueprint $table) {

            // 🔥 DROP FOREIGN KEY DULU
            $table->dropForeign(['partner_id']);

            // 🔥 BARU DROP COLUMN
            $table->dropColumn('partner_id');
        });
    }

    public function down()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable();

            $table->foreign('partner_id')
                ->references('id')
                ->on('family_members')
                ->nullOnDelete();
        });
    }
};
