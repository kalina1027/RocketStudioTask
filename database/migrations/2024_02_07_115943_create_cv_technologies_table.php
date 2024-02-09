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
        Schema::create('cv_technologies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cv_id')
                ->constrained(table: 'cvs')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('technology_id')
                ->constrained(table: 'technologies')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_technologies');
    }
};
