<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_inventory_assets')->insert([
            [
                'idassets' => 'AST001',
                'id_coa' => 'COA001',
                'id_rab_item' => 1,
                'id_dept' => 1,
                'category' => 'Elektronik',
                'id_sub_dept' => 101,
                'sub_category' => 'Laptop',
                'type' => 'Asset',
                'inv_type' => 'IT Equipment',
                'name' => 'Laptop Dell Inspiron',
                'id_brand' => 1,
                'brand' => 'Dell',
                'sku' => 'DLI123',
                'id_model' => 1,
                'model_number' => 'INS-14-5000',
                'color' => 'Silver',
                'vendor_preference' => 'VendorA',
                'qty' => 10.0000,
                'unit' => 'pcs',
                'hpp' => 7000000.00,
                'automargin' => 15.00,
                'minsales' => 7500000.00,
                'pricelist' => 8000000.00,
                'currency' => 'IDR',
                'lastpurch' => 6800000.00,
                'aktifyn' => 'Y',
                'wsprice' => 7700000.00,
                'category2' => 'Hardware',
                'plu' => 'PLU001',
                'wunit' => 'kg',
                'net_weight' => 2.50,
                'idsupplier' => 1,
                'file' => null,
                'img' => null,
                'img_name' => null,
                'description' => 'Laptop Dell untuk kebutuhan kantor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idassets' => 'AST002',
                'id_coa' => 'COA002',
                'id_rab_item' => 2,
                'id_dept' => 2,
                'category' => 'Furniture',
                'id_sub_dept' => 102,
                'sub_category' => 'Meja Kantor',
                'type' => 'Asset',
                'inv_type' => 'Office Furniture',
                'name' => 'Meja Kantor Kayu',
                'id_brand' => 2,
                'brand' => 'FurnitureX',
                'sku' => 'FRN567',
                'id_model' => 2,
                'model_number' => 'OFF-TBL-001',
                'color' => 'Brown',
                'vendor_preference' => 'VendorB',
                'qty' => 5.0000,
                'unit' => 'pcs',
                'hpp' => 1500000.00,
                'automargin' => 20.00,
                'minsales' => 1700000.00,
                'pricelist' => 1800000.00,
                'currency' => 'IDR',
                'lastpurch' => 1450000.00,
                'aktifyn' => 'Y',
                'wsprice' => 1750000.00,
                'category2' => 'Interior',
                'plu' => 'PLU002',
                'wunit' => 'kg',
                'net_weight' => 20.00,
                'idsupplier' => 2,
                'file' => null,
                'img' => null,
                'img_name' => null,
                'description' => 'Meja kantor kayu solid untuk ruangan staf',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
