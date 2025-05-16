<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SidebarItem;
use App\Models\Permission;

class SidebarItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing items
        SidebarItem::truncate();

        // Get permissions we'll need
        $permissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_booking',
            'process_checkin',
            'process_checkout',
            'purchasing',
            'view_purchase_request',
            'list_purchase_request',
            'create_purchase_request',
            'edit_purchase_request',
            'submit_purchase_request'
        ])->pluck('id', 'name');

        // Create parent items
        $dashboard = SidebarItem::create([
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'permission_id' => $permissions['view_dashboard'],
            'parent_id' => null,
            'order' => 1
        ]);

        $booking = SidebarItem::create([
            'name' => 'Booking',
            'route' => null,
            'permission_id' => $permissions['view_booking'],
            'parent_id' => null,
            'order' => 2
        ]);

        $purchasing = SidebarItem::create([
            'name' => 'Purchasing',
            'route' => null,
            'permission_id' => $permissions['purchasing'],
            'parent_id' => null,
            'order' => 3
        ]);

        // Create child items for Booking
        SidebarItem::create([
            'name' => 'Process Check-In',
            'route' => 'booking.checkin',
            'permission_id' => $permissions['process_checkin'],
            'parent_id' => $booking->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Process Check-Out',
            'route' => 'booking.checkout',
            'permission_id' => $permissions['process_checkout'],
            'parent_id' => $booking->id,
            'order' => 2
        ]);

        // Create child items for Purchasing
        SidebarItem::create([
            'name' => 'Purchase Request',
            'route' => 'purchasing.request',
            'permission_id' => $permissions['view_purchase_request'],
            'parent_id' => $purchasing->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'List Requests',
            'route' => 'purchasing.requests.list',
            'permission_id' => $permissions['list_purchase_request'],
            'parent_id' => $purchasing->id,
            'order' => 2
        ]);

        SidebarItem::create([
            'name' => 'Create Request',
            'route' => 'purchasing.requests.create',
            'permission_id' => $permissions['create_purchase_request'],
            'parent_id' => $purchasing->id,
            'order' => 3
        ]);
    }
}
