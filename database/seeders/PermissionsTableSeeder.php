<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            // Dashboard & Booking
            ['name' => 'view_dashboard'],
            ['name' => 'view_booking'],
            ['name' => 'process_checkin'],
            ['name' => 'process_checkout'],
            // Purchasing
            ['name' => 'purchasing'],
            ['name' => 'view_purchase_request'],
            ['name' => 'list_purchase_request'],
            ['name' => 'create_purchase_request'],
            ['name' => 'edit_purchase_request'],
            ['name' => 'submit_purchase_request'],           
          
        ]);
    }
}
