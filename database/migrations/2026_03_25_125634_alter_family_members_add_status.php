<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->boolean('is_dead')->default(false)->after('gender');
            $table->date('death_date')->nullable()->after('is_dead');
        });
    }

    public function down()
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn(['is_dead', 'death_date']);
        });
    }
};
