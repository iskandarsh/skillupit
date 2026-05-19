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
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();

            $table->string('photo')->nullable();

            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('type', ['single', 'couple'])->default('single');

            $table->foreignId('parent_id')->nullable()->constrained('family_members')->onDelete('cascade');
            $table->foreignId('partner_id')->nullable()->constrained('family_members')->onDelete('cascade');

            $table->boolean('collapsed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};
