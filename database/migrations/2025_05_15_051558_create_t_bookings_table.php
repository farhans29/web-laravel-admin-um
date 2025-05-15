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
        Schema::create('t_bookings', function (Blueprint $table) {
            $table->increments('idrec');
            $table->integer('property_id')->nullable();
            $table->string('order_id');
            $table->string('room_id');
            $table->dateTime('check_in_at');
            $table->dateTime('check_out_at')->nullable();
            $table->integer('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->integer('updated_by');
            $table->timestamp('updated_at')->useCurrent();
            $table->tinyInteger('activeyn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_bookings');
    }
};
