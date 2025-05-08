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
        Schema::create('m_vendors', function (Blueprint $table) {
            $table->id('idsupplier');
            $table->string('vendor_type')->nullable();
            $table->string('category')->nullable();
            $table->string('initials')->nullable();
            $table->string('company_type')->nullable();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('termin')->nullable();
            $table->string('bank_acc_num')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_name')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('npwp_id')->nullable();
            $table->text('npwp_address')->nullable();
            $table->string('npwp_city')->nullable();
            $table->string('npwp_country')->nullable();
            $table->string('npwp_zipcode')->nullable();
            $table->string('npwp_pdf')->nullable(); // path ke file PDF
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps(); // created_at & updated_at
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('m_vendors', function (Blueprint $table) {
            Schema::dropIfExists('m_vendors');
        });
    }
};
