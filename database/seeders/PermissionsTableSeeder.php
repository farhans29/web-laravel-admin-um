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
        $permissions = [
            // Management Section
            'Management',
            // Dashboard
            'view_dashboard',

            // Bookings
            'view_bookings',
            'view_all_bookings',
            'view_pending_bookings',
            'view_confirmed_bookings',
            'view_checkins',
            'view_checkouts',
            'view_completed_bookings',
            'view_change_room',

            // Properties
            'properties',
            'view_properties',
            'view_property_facilities',

            // Rooms
            'rooms',
            'view_rooms',
            'view_room_facilities',

            // Customers
            'view_customers',

            // Financial
            'financial',

            // Payments
            'view_payments',
            'view_invoices',
            'view_reports',

            // Settings
            'Settings',

            // User Management
            'view_users',
            'manage_roles',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
