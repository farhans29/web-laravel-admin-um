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
        Schema::create('t_booking', function (Blueprint $table) {
            $table->id('idrec');
            $table->integer('property_id')->nullable();
            $table->string('order_id', 100); // Sesuaikan panjang dengan t_transactions
            $table->string('room_id', 255);
            $table->dateTime('check_in_at');
            $table->dateTime('check_out_at')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_booking');
    }
};
