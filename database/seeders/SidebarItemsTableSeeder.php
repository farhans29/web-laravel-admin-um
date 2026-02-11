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

        // Get all permissions
        $permissions = Permission::all()->pluck('id', 'name')->toArray();

        // Create parent sections
        $managementSection = SidebarItem::create([
            'name' => 'Management',
            'route' => null,
            'permission_id' => $permissions['Management'] ?? null,
            'parent_id' => null,
            'order' => 1,
        ]);

        // Management items
        $dashboard = SidebarItem::create([
            'name' => 'Dashboard',
            'route' => 'dashboard',
            'permission_id' => $permissions['view_dashboard'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 1
        ]);

        $bookings = SidebarItem::create([
            'name' => 'Bookings',
            'route' => null,
            'permission_id' => $permissions['view_bookings'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 2,
        ]);

        // Bookings children
        SidebarItem::create([
            'name' => 'All Bookings',
            'route' => 'bookings.index',
            'permission_id' => $permissions['view_all_bookings'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Pending',
            'route' => 'pendings.index',
            'permission_id' => $permissions['view_pending_bookings'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 2
        ]);

        SidebarItem::create([
            'name' => 'Confirm Bookings',
            'route' => 'newReserv.index',
            'permission_id' => $permissions['view_confirmed_bookings'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 3
        ]);

        SidebarItem::create([
            'name' => 'Checked-ins',
            'route' => 'checkin.index',
            'permission_id' => $permissions['view_checkins'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 4
        ]);

        SidebarItem::create([
            'name' => 'Checked-outs',
            'route' => 'checkout.index',
            'permission_id' => $permissions['view_checkouts'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 5
        ]);

        SidebarItem::create([
            'name' => 'Completed',
            'route' => 'completed.index',
            'permission_id' => $permissions['view_completed_bookings'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 6
        ]);

        SidebarItem::create([
            'name' => 'Change Room',
            'route' => 'changerooom.index',
            'permission_id' => $permissions['view_change_room'] ?? null,
            'parent_id' => $bookings->id,
            'order' => 7
        ]);

        $properties = SidebarItem::create([
            'name' => 'Properties',
            'route' => null,
            'permission_id' => $permissions['properties'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 3,
        ]);

        // Properties children
        SidebarItem::create([
            'name' => 'Master Properties',
            'route' => 'properties.index',
            'permission_id' => $permissions['view_properties'] ?? null,
            'parent_id' => $properties->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Master Facilities',
            'route' => 'facilityProperty.index',
            'permission_id' => $permissions['view_property_facilities'] ?? null,
            'parent_id' => $properties->id,
            'order' => 2
        ]);

        SidebarItem::create([
            'name' => 'Deposit Fee Management',
            'route' => 'deposit-fees.index',
            'permission_id' => $permissions['view_deposit_fees'] ?? null,
            'parent_id' => $properties->id,
            'order' => 3
        ]);

        SidebarItem::create([
            'name' => 'Parking Fee Management',
            'route' => 'parking-fees.index',
            'permission_id' => $permissions['view_parking_fees'] ?? null,
            'parent_id' => $properties->id,
            'order' => 4
        ]);

        $rooms = SidebarItem::create([
            'name' => 'Rooms/Units',
            'route' => null,
            'permission_id' => $permissions['rooms'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 4,
        ]);

        // Rooms children
        SidebarItem::create([
            'name' => 'Master Rooms',
            'route' => 'rooms.index',
            'permission_id' => $permissions['view_rooms'] ?? null,
            'parent_id' => $rooms->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Master Facilities',
            'route' => 'facilityRooms.index',
            'permission_id' => $permissions['view_room_facilities'] ?? null,
            'parent_id' => $rooms->id,
            'order' => 2
        ]);

        $customers = SidebarItem::create([
            'name' => 'Customers',
            'route' => 'customers.index',
            'permission_id' => $permissions['view_customers'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 5
        ]);

        $roomAvailability = SidebarItem::create([
            'name' => 'Room Availability',
            'route' => 'room-availability.index',
            'permission_id' => $permissions['view_room_availability'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 6
        ]);

        $vouchers = SidebarItem::create([
            'name' => 'Vouchers',
            'route' => 'vouchers.index',
            'permission_id' => $permissions['view_vouchers'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 7
        ]);

        $promoBanners = SidebarItem::create([
            'name' => 'Promo Banners',
            'route' => 'promo-banners.index',
            'permission_id' => $permissions['view_promo_banners'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 9
        ]);

        $chat = SidebarItem::create([
            'name' => 'Chat',
            'route' => 'chat.index',
            'permission_id' => $permissions['manage_chat'] ?? null,
            'parent_id' => $managementSection->id,
            'order' => 8
        ]);

        // Financial items
        $financialSection = SidebarItem::create([
            'name' => 'Financial',
            'route' => null,
            'permission_id' => $permissions['financial'] ?? null,
            'parent_id' => null,
            'order' => 2,
        ]);
        
        $payments = SidebarItem::create([
            'name' => 'Payments',
            'route' => null,
            'permission_id' => $permissions['view_payments'] ?? null,
            'parent_id' => $financialSection->id,
            'order' => 1
        ]);

        // Payments children
        SidebarItem::create([
            'name' => 'Transaction',
            'route' => 'admin.payments.index',
            'permission_id' => $permissions['view_payments'] ?? null,
            'parent_id' => $payments->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Parking',
            'route' => 'admin.parking-payments.index',
            'permission_id' => $permissions['view_parking_payments'] ?? null,
            'parent_id' => $payments->id,
            'order' => 2
        ]);

        SidebarItem::create([
            'name' => 'Deposit',
            'route' => 'admin.deposit-payments.index',
            'permission_id' => $permissions['view_deposit_payments'] ?? null,
            'parent_id' => $payments->id,
            'order' => 3
        ]);

        $refunds = SidebarItem::create([
            'name' => 'Refunds',
            'route' => 'admin.refunds.index',
            'permission_id' => $permissions['view_refunds'] ?? null,
            'parent_id' => $financialSection->id,
            'order' => 2
        ]);

        $reports = SidebarItem::create([
            'name' => 'Reports',
            'route' => null,
            'permission_id' => $permissions['view_reports'] ?? null,
            'parent_id' => $financialSection->id,
            'order' => 3
        ]);

        // Reports children
        SidebarItem::create([
            'name' => 'Booking Report',
            'route' => 'reports.booking.index',
            'permission_id' => $permissions['view_booking_report'] ?? null,
            'parent_id' => $reports->id,
            'order' => 1
        ]);

        SidebarItem::create([
            'name' => 'Transaction Report',
            'route' => 'reports.payment.index',
            'permission_id' => $permissions['view_payment_report'] ?? null,
            'parent_id' => $reports->id,
            'order' => 2
        ]);

        SidebarItem::create([
            'name' => 'Parking Report',
            'route' => 'reports.parking.index',
            'permission_id' => $permissions['view_parking_report'] ?? null,
            'parent_id' => $reports->id,
            'order' => 3
        ]);

        SidebarItem::create([
            'name' => 'Deposit Report',
            'route' => 'reports.deposit.index',
            'permission_id' => $permissions['view_deposit_report'] ?? null,
            'parent_id' => $reports->id,
            'order' => 4
        ]);

        SidebarItem::create([
            'name' => 'Rented Rooms Report',
            'route' => 'reports.rented-rooms.index',
            'permission_id' => $permissions['view_rented_rooms_report'] ?? null,
            'parent_id' => $reports->id,
            'order' => 5
        ]);

        // Settings items
        $settingsSection = SidebarItem::create([
            'name' => 'Settings',
            'route' => null,
            'permission_id' => $permissions['Settings'] ?? null,
            'parent_id' => null,
            'order' => 3,
        ]);
        
        $users = SidebarItem::create([
            'name' => 'Users',
            'route' => 'users-management',
            'permission_id' => $permissions['view_users'] ?? null,
            'parent_id' => $settingsSection->id,
            'order' => 1
        ]);

        $rolePermission = SidebarItem::create([
            'name' => 'Role & Permission',
            'route' => 'master-role-management',
            'permission_id' => $permissions['manage_roles'] ?? null,
            'parent_id' => $settingsSection->id,
            'order' => 2
        ]);

        $settings = SidebarItem::create([
            'name' => 'Settings',
            'route' => 'users.show',
            'permission_id' => $permissions['manage_settings'] ?? null,
            'parent_id' => $settingsSection->id,
            'order' => 3
        ]);
    }
}
