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
            $table->longText('facility')->nullable();
            $table->longText('price')->nullable();
            $table->binary('attachment')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
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
        Schema::dropIfExists('m_rooms');
    }
};
