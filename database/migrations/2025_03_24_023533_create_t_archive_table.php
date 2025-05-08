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
        Schema::create('t_archive', function (Blueprint $table) {
            $table->id('idrec');
            $table->unsignedBigInteger('id_archieve');
            $table->date('date');
            $table->string('doc_type');
            $table->text('description')->nullable();
            $table->string('file_name');
            $table->binary('pdfblob');
            $table->char('active_y_n', 1)->default('Y');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_archive');
    }
};
