<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_warehouse')->insert([
            [
                'id_warehouse' => 1,
                'name_wh' => 'Main Warehouse',
                'address_wh' => 'Jl. Main Street No. 123',
                'city_wh' => 'Jakarta',
                'country_wh' => 'Indonesia',
                'zipcode_wh' => '12345',
                'phone_wh' => '021-7654321',
                'fax_wh' => '021-7654322',
                'last_entry_wh' => Carbon::now(),
                'id_user' => 'ADMIN',
                'pid_wh' => 'WH001',
                'status_wh' => 'ACTIVE',
                'is_allocateable_wh' => 'Y',
                'pid_transit_wh' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id_warehouse' => 2,
                'name_wh' => 'Branch Warehouse',
                'address_wh' => 'Jl. Branch Street No. 456',
                'city_wh' => 'Surabaya',
                'country_wh' => 'Indonesia',
                'zipcode_wh' => '67890',
                'phone_wh' => '031-7654321',
                'fax_wh' => '031-7654322',
                'last_entry_wh' => Carbon::now(),
                'id_user' => 'ADMIN',
                'pid_wh' => 'WH002',
                'status_wh' => 'ACTIVE',
                'is_allocateable_wh' => 'Y',
                'pid_transit_wh' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 