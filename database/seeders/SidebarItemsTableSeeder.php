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
            'view_bookings',
            'view_all_bookings',
            'view_checkins',
            'view_checkouts',
            'view_properties',
            'view_rooms',            
            'view_customers',
            'view_payments',
            'view_invoices',
            'view_reports',
            'manage_users',
            'manage_settings'
        ])->pluck('id', 'name');

        // Create parent sections
        $managementSection = SidebarItem::create([
            'name' => 'Management',
            'route' => null,
            'permission_id' => null,
            'parent_id' => null,
            'order' => 1,
            'is_section' => true
        ]);

        $financialSection = SidebarItem::create([
            'name' => 'Financial',
            'route' => null,
            'permission_id' => null,
            'parent_id' => null,
            'order' => 2,
            'is_section' => true
        ]);

        $settingsSection = SidebarItem::create([
            'name' => 'Settings',
            'route' => null,
            'permission_id' => null,
            'parent_id' => null,
            'order' => 3,
            'is_section' => true
        ]);

        // Management items
        $dashboard = SidebarItem::create([
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'permission_id' => $permissions['view_dashboard'],
            'parent_id' => $managementSection->id,
            'order' => 1
        ]);

        $bookings = SidebarItem::create([
            'name' => 'Bookings',
            'route' => null,
            'permission_id' => $permissions['view_bookings'],
            'parent_id' => $managementSection->id,
            'order' => 2,
            'has_children' => true
        ]);

        // Bookings children
        SidebarItem::create([
            'name' => 'All Bookings',
            'route' => 'bookings.index',
            'permission_id' => $permissions['view_all_bookings'],
            'parent_id' => $bookings->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'New Reservations',
            'route' => 'newReserv.index',
            'permission_id' => $permissions['view_bookings'],
            'parent_id' => $bookings->id,
            'order' => 2
        ]);

        SidebarItem::create([
            'name' => 'Check-ins',
            'route' => 'checkin.index',
            'permission_id' => $permissions['view_checkins'],
            'parent_id' => $bookings->id,
            'order' => 3
        ]);

        SidebarItem::create([
            'name' => 'Check-outs',
            'route' => 'checkout.index',
            'permission_id' => $permissions['view_checkouts'],
            'parent_id' => $bookings->id,
            'order' => 4
        ]);

        $properties = SidebarItem::create([
            'name' => 'Properties',
            'route' => null,
            'permission_id' => $permissions['view_properties'],
            'parent_id' => $managementSection->id,
            'order' => 3,
            'has_children' => true
        ]);

        // Properties children
        SidebarItem::create([
            'name' => 'Master Properties',
            'route' => 'properties.index',
            'permission_id' => $permissions['view_properties'],
            'parent_id' => $properties->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Master Rooms',
            'route' => 'rooms.index',
            'permission_id' => $permissions['view_rooms'],
            'parent_id' => $properties->id,
            'order' => 2
        ]);

        $roomUnits = SidebarItem::create([
            'name' => 'Rooms/Units',
            'route' => 'changerooom.index',
            'permission_id' => $permissions['view_room_units'],
            'parent_id' => $managementSection->id,
            'order' => 4
        ]);

        $customers = SidebarItem::create([
            'name' => 'Customers',
            'route' => 'progress',
            'permission_id' => $permissions['view_customers'],
            'parent_id' => $managementSection->id,
            'order' => 5
        ]);

        // Financial items
        $payments = SidebarItem::create([
            'name' => 'Payments',
            'route' => 'admin.payments.index',
            'permission_id' => $permissions['view_payments'],
            'parent_id' => $financialSection->id,
            'order' => 1
        ]);

        $invoices = SidebarItem::create([
            'name' => 'Invoices',
            'route' => 'progress',
            'permission_id' => $permissions['view_invoices'],
            'parent_id' => $financialSection->id,
            'order' => 2
        ]);

        $reports = SidebarItem::create([
            'name' => 'Reports',
            'route' => 'progress',
            'permission_id' => $permissions['view_reports'],
            'parent_id' => $financialSection->id,
            'order' => 3
        ]);

        // Settings items
        $users = SidebarItem::create([
            'name' => 'Users',
            'route' => 'users-access-management',
            'permission_id' => $permissions['manage_users'],
            'parent_id' => $settingsSection->id,
            'order' => 1
        ]);

        $settings = SidebarItem::create([
            'name' => 'Settings',
            'route' => 'progress',
            'permission_id' => $permissions['manage_settings'],
            'parent_id' => $settingsSection->id,
            'order' => 2
        ]);
    }
}
