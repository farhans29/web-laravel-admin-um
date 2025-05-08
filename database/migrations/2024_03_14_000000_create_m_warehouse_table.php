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
        if (!Schema::hasTable('m_warehouse')) {
            Schema::create('m_warehouse', function (Blueprint $table) {
                $table->integer('id_warehouse')->primary();
                $table->string('name_wh');
                $table->string('address_wh');
                $table->string('city_wh');
                $table->string('country_wh');
                $table->string('zipcode_wh');
                $table->string('phone_wh');
                $table->string('fax_wh');
                $table->dateTime('last_entry_wh');
                $table->string('id_user');
                $table->string('pid_wh');
                $table->string('status_wh');
                $table->string('is_allocateable_wh');
                $table->decimal('pid_transit_wh', 18, 0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_warehouse');
    }
}; 