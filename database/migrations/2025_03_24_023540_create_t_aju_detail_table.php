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
        Schema::create('t_aju_detail', function (Blueprint $table) {
            $table->id('idrec');
            $table->unsignedBigInteger('id_aju');
            $table->unsignedBigInteger('id_archieve');

            $table->foreign('id_aju')->references('id_aju')->on('t_aju')->onDelete('cascade');
            $table->foreign('id_archieve')->references('idrec')->on('t_archive')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_aju_detail');
    }
};
