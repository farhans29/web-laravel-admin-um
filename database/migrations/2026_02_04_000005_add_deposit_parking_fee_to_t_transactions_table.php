<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET SESSION sql_mode = ""');

        Schema::table('t_transactions', function (Blueprint $table) {
            $table->integer('deposit_fee')->default(0)->after('grandtotal_price');
            $table->integer('parking_fee')->default(0)->after('deposit_fee');
        });

        DB::statement("SET SESSION sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    }

    public function down(): void
    {
        Schema::table('t_transactions', function (Blueprint $table) {
            $table->dropColumn(['deposit_fee', 'parking_fee']);
        });
    }
};
