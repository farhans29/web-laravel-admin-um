<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMInventoryAssetsTable extends Migration
{
    public function up()
    {
        Schema::create('m_inventory_assets', function (Blueprint $table) {
            $table->string('idassets', 50)->primary(); // idassets sebagai primary key
            $table->string('id_coa', 50)->nullable();
            $table->integer('id_rab_item')->nullable();
            $table->integer('id_dept')->nullable();
            $table->string('category', 50)->nullable();
            $table->integer('id_sub_dept')->nullable();
            $table->string('sub_category', 100)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('inv_type', 50)->nullable();
            $table->string('name', 200)->nullable();
            $table->integer('id_brand')->nullable();
            $table->string('brand', 20)->nullable();
            $table->string('sku', 20)->nullable();
            $table->integer('id_model')->nullable();
            $table->string('model_number', 50)->nullable();
            $table->string('color', 20)->nullable();
            $table->string('vendor_preference', 50)->nullable();
            $table->decimal('qty', 18, 4)->nullable();
            $table->string('unit', 50)->nullable();
            $table->decimal('hpp', 18, 2)->nullable();
            $table->decimal('automargin', 18, 2)->nullable();
            $table->decimal('minsales', 18, 2)->nullable();
            $table->decimal('pricelist', 18, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->decimal('lastpurch', 18, 2)->nullable();
            $table->string('aktifyn', 10)->nullable();
            $table->decimal('wsprice', 18, 2)->nullable();
            $table->string('category2', 50)->nullable();
            $table->string('plu', 10)->nullable();
            $table->string('wunit', 20)->nullable();
            $table->decimal('net_weight', 18, 2)->nullable();
            $table->integer('idsupplier')->nullable();
            $table->binary('file')->nullable();
            $table->binary('img')->nullable();
            $table->string('img_name', 150)->nullable();
            $table->string('description', 2048)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('m_inventory_assets');
    }
}
