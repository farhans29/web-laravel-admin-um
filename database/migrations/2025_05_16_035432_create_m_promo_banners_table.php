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
        Schema::create('m_promo_banners', function (Blueprint $table) {
            $table->integer('idrec')->primary();
            $table->string('title', 250)->nullable();
            $table->binary('attachment')->nullable();
            $table->integer('descriptions')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_promo_banners');
    }
};
