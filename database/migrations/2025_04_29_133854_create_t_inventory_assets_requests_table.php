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
        Schema::create('t_inventory_assets_requests', function (Blueprint $table) {
            $table->increments('idrec');
            $table->string('idreqform', 100)->nullable();
            $table->string('pr_title', 200)->nullable();
            $table->string('pr_type', 225)->nullable();
            $table->string('id_rab', 50)->nullable();
            $table->date('pr_date')->nullable();
            $table->date('rab_date')->nullable();
            $table->string('applicant', 100)->nullable();
            $table->string('company_id', 100)->nullable();
            $table->string('currency', 50)->nullable();
            $table->string('payment_by', 10)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('division', 100)->nullable();
            $table->string('purch_type', 50)->nullable();
            $table->string('approval_to', 100)->nullable();
            $table->integer('approval2_to')->nullable();
            $table->integer('approval3_to')->nullable();
            $table->string('reqlevel', 100)->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('note', 500)->nullable();
            $table->integer('idsupplier')->nullable();
            $table->integer('id_warehouse')->nullable();
            $table->string('pic', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('delivery_address', 1084)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->decimal('subtotal', 18, 4)->nullable();
            $table->decimal('discount', 18, 4)->nullable();
            $table->decimal('total', 18, 4)->nullable();
            $table->decimal('ppn', 18, 4)->nullable();
            $table->date('approvaldate')->nullable();
            $table->string('approvalstat', 50)->nullable();
            $table->string('approved1by', 50)->nullable();
            $table->string('approved2by', 50)->nullable();
            $table->string('approved3by', 50)->nullable();
            $table->string('approval1_status', 20)->nullable();
            $table->string('approval2_status', 20)->nullable();
            $table->string('approval3_status', 20)->nullable();
            $table->decimal('gtotal', 18, 4)->nullable();
            $table->decimal('balance', 18, 4)->default(0.0000);
            $table->decimal('delivery_charge', 18, 4)->nullable();
            $table->text('remarks1')->nullable();
            $table->text('remarks2')->nullable();
            $table->text('remarks3')->nullable();
            $table->string('prepared_by', 50)->nullable();
            $table->string('reviewed_by', 50)->nullable();
            $table->string('reviewed2_by', 50)->nullable();
            $table->string('approved_by', 50)->nullable();
            $table->date('prepared_date')->nullable();
            $table->date('reviewed_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->char('print_status', 1)->nullable();
            $table->char('price_updated', 1)->nullable();
            $table->binary('pr_file')->nullable();
            $table->binary('quotation1')->nullable();
            $table->binary('quotation2')->nullable();
            $table->binary('quotation3')->nullable();
            $table->char('quotation_approval1', 1)->nullable();
            $table->char('quotation_approval2', 1)->nullable();
            $table->char('quotation_approval3', 1)->nullable();
            $table->string('vendor_quo1', 250)->nullable();
            $table->string('vendor_quo2', 250)->nullable();
            $table->string('vendor_quo3', 250)->nullable();
            $table->decimal('total_quo1', 18, 4)->nullable();
            $table->decimal('total_quo2', 18, 4)->nullable();
            $table->decimal('total_quo3', 18, 4)->nullable();
            $table->date('created_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->date('updated_at')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_inventory_assets_requests');
    }
};
