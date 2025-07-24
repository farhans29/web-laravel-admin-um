<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Role-Permission mappings
        $rolePermissions = [
            // Owner - has all permissions
            [1, 1],  // view_dashboard
            [1, 2],  // view_booking
            [1, 3],  // process_checkin
            [1, 4],  // process_checkout
            [1, 5],  // view_bookings
            [1, 6],  // view_all_bookings
            [1, 7],  // view_checkins
            [1, 8],  // view_checkouts
            [1, 9],  // view_properties
            [1, 10], // view_rooms
            [1, 11], // view_customers
            [1, 12], // view_payments
            [1, 13], // view_invoices
            [1, 14], // view_reports
            [1, 15], // manage_users
            [1, 16], // manage_settings

            // Manager - most permissions except user management
            [2, 1],  // view_dashboard
            [2, 2],  // view_booking
            [2, 3],  // process_checkin
            [2, 4],  // process_checkout
            [2, 5],  // view_bookings
            [2, 6],  // view_all_bookings
            [2, 7],  // view_checkins
            [2, 8],  // view_checkouts
            [2, 9],  // view_properties
            [2, 10], // view_rooms
            [2, 11], // view_customers
            [2, 12], // view_payments
            [2, 13], // view_invoices
            [2, 14], // view_reports

            // Front Desk - booking and customer related permissions
            [3, 1],  // view_dashboard
            [3, 2],  // view_booking
            [3, 3],  // process_checkin
            [3, 4],  // process_checkout
            [3, 5],  // view_bookings
            [3, 7],  // view_checkins
            [3, 8],  // view_checkouts
            [3, 10], // view_rooms
            [3, 11], // view_customers

            // Finance - payment and invoice related permissions
            [4, 1],  // view_dashboard
            [4, 5],  // view_bookings
            [4, 11], // view_customers
            [4, 12], // view_payments
            [4, 13], // view_invoices
            [4, 14], // view_reports

            // CS (Customer Service) - customer and booking related permissions
            [5, 1],  // view_dashboard
            [5, 2],  // view_booking
            [5, 5],  // view_bookings
            [5, 11], // view_customers
            [5, 13], // view_invoices

            // Sales - booking and customer related permissions
            [6, 1],  // view_dashboard
            [6, 2],  // view_booking
            [6, 5],  // view_bookings
            [6, 9],  // view_properties
            [6, 10], // view_rooms
            [6, 11], // view_customers
            [6, 14], // view_reports

            // Property Owner - property and booking related permissions
            [7, 1],  // view_dashboard
            [7, 5],  // view_bookings
            [7, 9],  // view_properties
            [7, 10], // view_rooms
            [7, 14], // view_reports
        ];

        $data = array_map(function ($rolePermission) use ($now) {
            return [
                'role_id' => $rolePermission[0],
                'permission_id' => $rolePermission[1],
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 1, // Assuming Admin (user_id 1) is creating these
                'updated_by' => 1,
            ];
        }, $rolePermissions);

        DB::table('role_permission')->insert($data);
    }
}
