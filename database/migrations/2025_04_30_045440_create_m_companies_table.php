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
        Schema::create('m_companies', function (Blueprint $table) {
            $table->integer('id_company')->primary();
            $table->string('initials', 5);
            $table->string('company_type', 10)->nullable();
            $table->string('name', 50);
            $table->string('address', 1024);
            $table->string('city', 50);
            $table->string('country', 50);
            $table->string('zip_code', 10);
            $table->string('npwp_id', 50)->nullable();
            $table->string('npwp_address', 350)->nullable();
            $table->string('npwp_city', 50)->nullable();
            $table->string('npwp_country', 50)->nullable();
            $table->string('npwp_zipcode', 50)->nullable();
            $table->binary('npwp_pdf')->nullable();
            $table->binary('logo_blob')->nullable();
            $table->string('logo_filename', 100)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('status', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_companies');
    }
};
