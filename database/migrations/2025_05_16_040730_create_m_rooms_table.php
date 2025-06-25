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
        Schema::create('m_rooms', function (Blueprint $table) {
            $table->integer('idrec')->primary();
            $table->integer('property_id')->nullable();
            $table->string('property_name', 100)->nullable();
            $table->string('slug', 200)->nullable();
            $table->string('name', 100)->nullable();
            $table->text('descriptions')->nullable();
            $table->longText('periode')->nullable();
            $table->string('type', 100)->nullable();
            $table->string('level', 10)->nullable();
            $table->string('bed_type', 50)->nullable();
            $table->binary('image')->nullable();
            $table->string('capacity', 10)->nullable();

            $table->json('facility')->nullable();

            $table->longText('price')->nullable();

            // Tambahan kolom baru sesuai permintaan
            $table->decimal('admin_fees', 18, 4)->nullable();
            $table->decimal('discount_percent', 18, 4)->nullable();
            $table->decimal('price_original_daily', 18, 4)->nullable();
            $table->decimal('price_discounted_daily', 18, 4)->nullable();
            $table->decimal('price_original_monthly', 18, 4)->nullable();
            $table->decimal('price_discounted_monthly', 18, 4)->nullable();          
           
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_rooms');
    }
};
