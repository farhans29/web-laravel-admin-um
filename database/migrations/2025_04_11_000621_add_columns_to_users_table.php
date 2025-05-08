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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dep')->nullable()->after('role_id'); 
            $table->string('status')->default('active')->after('dep'); 
            $table->unsignedBigInteger('created_by')->nullable()->after('status'); 
            $table->unsignedBigInteger('updated_by')->nullable()->after('created_by'); 
            $table->string('employee_id')->nullable()->after('updated_by'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dep', 'status', 'created_by', 'updated_by', 'employee_id']);
        });
    }
};
