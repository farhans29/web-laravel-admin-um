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
        Schema::table('t_archive', function (Blueprint $table) {
            $table->string('no_document')->after('doc_type');
            $table->integer('sub_dep')->after('no_document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_archive', function (Blueprint $table) {
            $table->dropColumn(['no_document', 'sub_dep']);
        });
    }
};
