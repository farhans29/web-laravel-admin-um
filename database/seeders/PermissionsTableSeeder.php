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
            ['name' => 'view_bookings'],
            ['name' => 'view_all_bookings'],
            ['name' => 'view_checkins'],
            ['name' => 'view_checkouts'],
            ['name' => 'view_properties'],
            ['name' => 'view_rooms'],            
            ['name' => 'view_customers'],
            ['name' => 'view_payments'],
            ['name' => 'view_invoices'],
            ['name' => 'view_reports'], 
            ['name' => 'manage_users'],
            ['name' => 'manage_settings'],           
        ]);
    }
}
