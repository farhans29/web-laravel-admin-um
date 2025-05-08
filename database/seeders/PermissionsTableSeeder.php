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
            // Purchasing
            ['name' => 'purchasing'],
            ['name' => 'view_purchase_request'],
            ['name' => 'list_purchase_request'],
            ['name' => 'create_purchase_request'],
            ['name' => 'edit_purchase_request'],
            ['name' => 'submit_purchase_request'],
            // Sales
            ['name' => 'sales'],
            ['name' => 'view_promotions'],
            ['name' => 'list_promotions'],
            ['name' => 'create_promotions'],
            ['name' => 'edit_promotions'],
            ['name' => 'delete_promotions'],
            // Warehouse
            ['name' => 'warehouse'],
            ['name' => 'view_inventory'],
            ['name' => 'list_inventory'],
            ['name' => 'create_inventory'],
            ['name' => 'edit_inventory'],
            ['name' => 'delete_inventory'],
            // Accounting
            ['name' => 'accounting'],
            ['name' => 'view_accounts'],
            ['name' => 'list_accounts'],
            ['name' => 'create_accounts'],
            ['name' => 'edit_accounts'],
            ['name' => 'delete_accounts'],
            ['name' => 'view_invoice'],
            ['name' => 'list_invoice'],
            ['name' => 'create_invoice'],
            ['name' => 'edit_invoice'],
            ['name' => 'delete_invoice'],
            // Finance
            ['name' => 'finance'],
            ['name' => 'view_payment'],
            ['name' => 'list_payments'],
            ['name' => 'create_payments'],
            ['name' => 'edit_payments'],
            ['name' => 'delete_payments'],
            ['name' => 'view_reports'],
            ['name' => 'list_reports'],
            ['name' => 'create_reports'],
            ['name' => 'edit_reports'],
            ['name' => 'delete_reports'],
            // Tax
            ['name' => 'tax'],
            ['name' => 'view_tax_payment'],
            ['name' => 'list_tax_payments'],
            ['name' => 'create_tax_payments'],
            ['name' => 'edit_tax_payments'],
            ['name' => 'delete_tax_payments'],
            // Human Resource
            ['name' => 'human_resource'],
            ['name' => 'view_employees'],
            ['name' => 'list_employees'],
            ['name' => 'create_employees'],
            ['name' => 'edit_employees'],
            ['name' => 'delete_employees'],
            ['name' => 'view_payroll'],
            ['name' => 'list_payroll'],
            ['name' => 'create_payroll'],
            ['name' => 'edit_payroll'],
            ['name' => 'delete_payroll'],
            // General Affairs
            ['name' => 'general_affairs'],
            ['name' => 'view_office_supplies'],
            ['name' => 'list_office_supplies'],
            ['name' => 'create_office_supplies'],
            ['name' => 'edit_office_supplies'],
            ['name' => 'delete_office_supplies'],
            ['name' => 'view_facilities'],
            ['name' => 'list_facilities'],
            ['name' => 'create_facilities'],
            ['name' => 'edit_facilities'],
            ['name' => 'delete_facilities'],
            // Logistics
            ['name' => 'logistics'],
            ['name' => 'view_shipping'],
            ['name' => 'list_shipping'],
            ['name' => 'create_shipping'],
            ['name' => 'edit_shipping'],
            ['name' => 'delete_shipping'],
            // Archive
            ['name' => 'aju'],
            ['name' => 'view_aju'],
            ['name' => 'list_aju'],
            ['name' => 'create_aju'],
            ['name' => 'edit_aju'],
            ['name' => 'delete_aju'],
            ['name' => 'view_document'],
            ['name' => 'list_document'],
            ['name' => 'create_document'],
            ['name' => 'edit_document'],
            ['name' => 'delete_document'],
            // Master
            ['name' => 'master'],
            ['name' => 'view_master_department'],
            ['name' => 'list_master_department'],
            ['name' => 'create_master_department'],
            ['name' => 'edit_master_department'],
            ['name' => 'delete_master_department'],
            // Account Settings
            ['name' => 'account_settings'],
            ['name' => 'view_os_menu'],
            ['name' => 'view_user_settings'],
            ['name' => 'list_user_settings'],
            ['name' => 'create_user_settings'],
            ['name' => 'edit_user_settings'],
            ['name' => 'delete_user_settings'],
          
        ]);
    }
}
