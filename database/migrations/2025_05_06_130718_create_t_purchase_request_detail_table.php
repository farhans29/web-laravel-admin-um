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
        Schema::create('t_purchase_request_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idrecrab');
            $table->string('idreqform', 50)->nullable();
            $table->string('idpr', 20)->nullable();
            $table->string('id_rab', 50)->nullable();
            $table->string('idassets', 50)->nullable();
            $table->string('name_detail', 100)->nullable();
            $table->decimal('qty', 18, 4)->nullable();
            $table->decimal('qtyBalance', 18, 4)->default(0.0000);
            $table->string('unit', 20)->nullable();
            $table->string('currency', 5)->nullable();
            $table->decimal('price', 18, 4)->nullable();
            $table->decimal('total', 18, 4)->nullable();
            $table->decimal('balance', 18, 4)->default(0.0000);
            $table->decimal('balance_rab', 18, 4)->default(0.0000);
            $table->string('remarks', 200)->nullable();
            $table->integer('idsupplier')->nullable();
            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->string('status', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_purchase_request_detail');
    }
};
