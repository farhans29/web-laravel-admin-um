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
        // -------------------- Purchasing --------------------
        $purchasing = SidebarItem::create([
            'name' => 'Purchasing',
            'route' => null,
            'permission_id' => Permission::where('name', 'purchasing')->first()->id,
            'parent_id' => null,
            'order' => 1,
        ]);

        $purchaseRequest = SidebarItem::create([
            'name' => 'Purchase Request',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_purchase_request')->first()->id,
            'parent_id' => $purchasing->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'purchase_requests.index',
            'permission_id' => Permission::where('name', 'list_purchase_request')->first()->id,
            'parent_id' => $purchaseRequest->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'purchase_requests.create',
            'permission_id' => Permission::where('name', 'create_purchase_request')->first()->id,
            'parent_id' => $purchaseRequest->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'purchase_requests.edit',
            'permission_id' => Permission::where('name', 'edit_purchase_request')->first()->id,
            'parent_id' => $purchaseRequest->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'purchase_requests.submit',
            'permission_id' => Permission::where('name', 'submit_purchase_request')->first()->id,
            'parent_id' => $purchaseRequest->id,
            'order' => 4,
        ]);

        // -------------------- Sales --------------------
        $sales = SidebarItem::create([
            'name' => 'Sales',
            'route' => null,
            'permission_id' => Permission::where('name', 'sales')->first()->id,
            'parent_id' => null,
            'order' => 2, 
        ]);

        $promotion = SidebarItem::create([
            'name' => 'Promotion',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_promotions')->first()->id,
            'parent_id' => $sales->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'promotions.index',
            'permission_id' => Permission::where('name', 'list_promotions')->first()->id,
            'parent_id' => $promotion->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'promotions.create',
            'permission_id' => Permission::where('name', 'create_promotions')->first()->id,
            'parent_id' => $promotion->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'promotions.edit',
            'permission_id' => Permission::where('name', 'edit_promotions')->first()->id,
            'parent_id' => $promotion->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'promotions.delete',
            'permission_id' => Permission::where('name', 'delete_promotions')->first()->id,
            'parent_id' => $promotion->id,
            'order' => 4,
        ]);

        // -------------------- Warehouse --------------------
        $warehouse = SidebarItem::create([
            'name' => 'Warehouse',
            'route' => null,
            'permission_id' => Permission::where('name', 'warehouse')->first()->id,
            'parent_id' => null,
            'order' => 3, 
        ]);

        
        $inventory = SidebarItem::create([
            'name' => 'Inventory',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_inventory')->first()->id,
            'parent_id' => $warehouse->id,
            'order' => 1, 
        ]);
        
        SidebarItem::create([
            'name' => 'List',
            'route' => 'inventory.index',
            'permission_id' => Permission::where('name', 'list_inventory')->first()->id,
            'parent_id' => $inventory->id,
            'order' => 1,
        ]);
        
        SidebarItem::create([
            'name' => 'New',
            'route' => 'inventory.create',
            'permission_id' => Permission::where('name', 'create_inventory')->first()->id,
            'parent_id' => $inventory->id,
            'order' => 2,
        ]);
        
        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'inventory.edit',
            'permission_id' => Permission::where('name', 'edit_inventory')->first()->id,
            'parent_id' => $inventory->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'inventory.delete',
            'permission_id' => Permission::where('name', 'delete_inventory')->first()->id,
            'parent_id' => $inventory->id,
            'order' => 4,
        ]);


        // -------------------- Accounting --------------------
        $accounting = SidebarItem::create([
            'name' => 'Accounting',
            'route' => null,
            'permission_id' => Permission::where('name', 'accounting')->first()->id,
            'parent_id' => null,
            'order' => 4,
        ]);

        $accounts = SidebarItem::create([
            'name' => 'Accounts',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_accounts')->first()->id,
            'parent_id' => $accounting->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'accounts.index',
            'permission_id' => Permission::where('name', 'list_accounts')->first()->id,
            'parent_id' => $accounts->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'accounts.create',
            'permission_id' => Permission::where('name', 'create_accounts')->first()->id,
            'parent_id' => $accounts->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'accounts.edit',
            'permission_id' => Permission::where('name', 'edit_accounts')->first()->id,
            'parent_id' => $accounts->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'accounts.delete',
            'permission_id' => Permission::where('name', 'delete_accounts')->first()->id,
            'parent_id' => $accounts->id,
            'order' => 4,
        ]);

        $invoice = SidebarItem::create([
            'name' => 'Invoice',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_invoice')->first()->id,
            'parent_id' => $accounting->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'invoices.index',
            'permission_id' => Permission::where('name', 'list_invoice')->first()->id,
            'parent_id' => $invoice->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'invoices.create',
            'permission_id' => Permission::where('name', 'create_invoice')->first()->id,
            'parent_id' => $invoice->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'invoices.edit',
            'permission_id' => Permission::where('name', 'edit_invoice')->first()->id,
            'parent_id' => $invoice->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'invoices.delete',
            'permission_id' => Permission::where('name', 'delete_invoice')->first()->id,
            'parent_id' => $invoice->id,
            'order' => 4,
        ]);

        // -------------------- Finance --------------------
        $finance = SidebarItem::create([
            'name' => 'Finance',
            'route' => null,
            'permission_id' => Permission::where('name', 'finance')->first()->id,
            'parent_id' => null,
            'order' => 5, 
        ]);

        $payment = SidebarItem::create([
            'name' => 'Payment',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_payment')->first()->id,
            'parent_id' => $finance->id,
            'order' => 1, 
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'payments.index',
            'permission_id' => Permission::where('name', 'list_payments')->first()->id,
            'parent_id' =>  $payment->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'payments.create',
            'permission_id' => Permission::where('name', 'create_payments')->first()->id,
            'parent_id' =>  $payment->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'payments.edit',
            'permission_id' => Permission::where('name', 'edit_payments')->first()->id,
            'parent_id' =>  $payment->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'payments.delete',
            'permission_id' => Permission::where('name', 'delete_payments')->first()->id,
            'parent_id' =>  $payment->id,
            'order' => 4,
        ]);

        $reports = SidebarItem::create([
            'name' => 'Reports',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_reports')->first()->id,
            'parent_id' => $finance->id,
            'order' => 2, 
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'reports.index',
            'permission_id' => Permission::where('name', 'list_reports')->first()->id,
            'parent_id' => $reports->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'reports.create',
            'permission_id' => Permission::where('name', 'create_reports')->first()->id,
            'parent_id' => $reports->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'reports.edit',
            'permission_id' => Permission::where('name', 'edit_reports')->first()->id,
            'parent_id' => $reports->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'reports.delete',
            'permission_id' => Permission::where('name', 'delete_reports')->first()->id,
            'parent_id' => $reports->id,
            'order' => 4,
        ]);



        // -------------------- Tax --------------------
        $tax = SidebarItem::create([
            'name' => 'Tax',
            'route' => null,
            'permission_id' => Permission::where('name', 'tax')->first()->id,
            'parent_id' => null,
            'order' => 6, 
        ]);

        $taxPayment = SidebarItem::create([
            'name' => 'TaxPayment',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_tax_payment')->first()->id,
            'parent_id' => $tax->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'taxPayments.index',
            'permission_id' => Permission::where('name', 'list_tax_payments')->first()->id,
            'parent_id' =>  $taxPayment->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'taxPayments.create',
            'permission_id' => Permission::where('name', 'create_tax_payments')->first()->id,
            'parent_id' =>  $taxPayment->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'taxPayments.edit',
            'permission_id' => Permission::where('name', 'edit_tax_payments')->first()->id,
            'parent_id' =>  $taxPayment->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'taxPayments.delete',
            'permission_id' => Permission::where('name', 'delete_tax_payments')->first()->id,
            'parent_id' =>  $taxPayment->id,
            'order' => 4,
        ]);

        // -------------------- Human Resource -------------------- 
        $humanResource = SidebarItem::create([
            'name' => 'Human Resource',
            'route' => null,
            'permission_id' => Permission::where('name', 'human_resource')->first()->id,
            'parent_id' => null,
            'order' => 7,
        ]);

        $employees = SidebarItem::create([
            'name' => 'Employees',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_employees')->first()->id,
            'parent_id' => $humanResource->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'employees.index',
            'permission_id' => Permission::where('name', 'list_employees')->first()->id,
            'parent_id' => $employees->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'employees.create',
            'permission_id' => Permission::where('name', 'create_employees')->first()->id,
            'parent_id' => $employees->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'employees.edit',
            'permission_id' => Permission::where('name', 'edit_employees')->first()->id,
            'parent_id' => $employees->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'employees.delete',
            'permission_id' => Permission::where('name', 'delete_employees')->first()->id,
            'parent_id' => $employees->id,
            'order' => 4,
        ]);

        $payroll = SidebarItem::create([
            'name' => 'Payroll',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_payroll')->first()->id,
            'parent_id' => $humanResource->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'payroll.index',
            'permission_id' => Permission::where('name', 'list_payroll')->first()->id,
            'parent_id' => $payroll->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'payroll.create',
            'permission_id' => Permission::where('name', 'create_payroll')->first()->id,
            'parent_id' => $payroll->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'payroll.edit',
            'permission_id' => Permission::where('name', 'edit_payroll')->first()->id,
            'parent_id' => $payroll->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'payroll.delete',
            'permission_id' => Permission::where('name', 'delete_payroll')->first()->id,
            'parent_id' => $payroll->id,
            'order' => 4,
        ]);


        // -------------------- General Affairs -------------------- 
        $generalAffairs = SidebarItem::create([
            'name' => 'General Affairs',
            'route' => null,
            'permission_id' => Permission::where('name', 'general_affairs')->first()->id,
            'parent_id' => null,
            'order' => 8, 
        ]);

        // Office Supplies
        $officeSupplies = SidebarItem::create([
            'name' => 'Office Supplies',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_office_supplies')->first()->id,
            'parent_id' => $generalAffairs->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'office_supplies.index',
            'permission_id' => Permission::where('name', 'list_office_supplies')->first()->id,
            'parent_id' => $officeSupplies->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'office_supplies.create',
            'permission_id' => Permission::where('name', 'create_office_supplies')->first()->id,
            'parent_id' => $officeSupplies->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'office_supplies.edit',
            'permission_id' => Permission::where('name', 'edit_office_supplies')->first()->id,
            'parent_id' => $officeSupplies->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'office_supplies.delete',
            'permission_id' => Permission::where('name', 'delete_office_supplies')->first()->id,
            'parent_id' => $officeSupplies->id,
            'order' => 4,
        ]);

        // Facilities
        $facilities = SidebarItem::create([
            'name' => 'Facilities',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_facilities')->first()->id,
            'parent_id' => $generalAffairs->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'facilities.index',
            'permission_id' => Permission::where('name', 'list_facilities')->first()->id,
            'parent_id' => $facilities->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'facilities.create',
            'permission_id' => Permission::where('name', 'create_facilities')->first()->id,
            'parent_id' => $facilities->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'facilities.edit',
            'permission_id' => Permission::where('name', 'edit_facilities')->first()->id,
            'parent_id' => $facilities->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'facilities.delete',
            'permission_id' => Permission::where('name', 'delete_facilities')->first()->id,
            'parent_id' => $facilities->id,
            'order' => 4,
        ]);


        // -------------------- Logistics --------------------
        $logistics = SidebarItem::create([
            'name' => 'Logistics',
            'route' => null,
            'permission_id' => Permission::where('name', 'logistics')->first()->id,
            'parent_id' => null,
            'order' => 9, 
        ]);

        $shipping = SidebarItem::create([
            'name' => 'Shipping',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_shipping')->first()->id,
            'parent_id' => $logistics->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'shipping.index',
            'permission_id' => Permission::where('name', 'list_shipping')->first()->id,
            'parent_id' => $shipping->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'shipping.create',
            'permission_id' => Permission::where('name', 'create_shipping')->first()->id,
            'parent_id' => $shipping->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'shipping.edit',
            'permission_id' => Permission::where('name', 'edit_shipping')->first()->id,
            'parent_id' => $shipping->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'shipping.delete',
            'permission_id' => Permission::where('name', 'delete_shipping')->first()->id,
            'parent_id' => $shipping->id,
            'order' => 4,
        ]);

        // -------------------- Archive -------------------- 
        $Archive = SidebarItem::create([
            'name' => 'Archive',
            'route' => null,
            'permission_id' => Permission::where('name', 'AJU')->first()->id,
            'parent_id' => null,
            'order' => 10, 
        ]);

        // Office Supplies
        $aju = SidebarItem::create([
            'name' => 'AJU',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_aju')->first()->id,
            'parent_id' => $Archive->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'aju.index',
            'permission_id' => Permission::where('name', 'list_aju')->first()->id,
            'parent_id' => $aju->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'aju.create',
            'permission_id' => Permission::where('name', 'create_aju')->first()->id,
            'parent_id' => $aju->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'aju.edit',
            'permission_id' => Permission::where('name', 'edit_aju')->first()->id,
            'parent_id' => $aju->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'aju.delete',
            'permission_id' => Permission::where('name', 'delete_aju')->first()->id,
            'parent_id' => $aju->id,
            'order' => 4,
        ]);

        $document = SidebarItem::create([
            'name' => 'Document',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_document')->first()->id,
            'parent_id' => $Archive->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'document.index',
            'permission_id' => Permission::where('name', 'list_document')->first()->id,
            'parent_id' => $document->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'document.create',
            'permission_id' => Permission::where('name', 'edit_document')->first()->id,
            'parent_id' => $document->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'document.edit',
            'permission_id' => Permission::where('name', 'delete_document')->first()->id,
            'parent_id' => $document->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'document.delete',
            'permission_id' => Permission::where('name', 'delete_facilities')->first()->id,
            'parent_id' => $document->id,
            'order' => 4,
        ]);

        // -------------------- Master --------------------
        $master = SidebarItem::create([
            'name' => 'Master',
            'route' => null,
            'permission_id' => Permission::where('name', 'master')->first()->id,
            'parent_id' => null,
            'order' => 11,
        ]);

        $department = SidebarItem::create([
            'name' => 'Department',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_master_department')->first()->id,
            'parent_id' => $master->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'department.index',
            'permission_id' => Permission::where('name', 'list_master_department')->first()->id,
            'parent_id' => $department->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'department.create',
            'permission_id' => Permission::where('name', 'create_master_department')->first()->id,
            'parent_id' => $department->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'department.edit',
            'permission_id' => Permission::where('name', 'edit_master_department')->first()->id,
            'parent_id' => $department->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'department.delete',
            'permission_id' => Permission::where('name', 'delete_master_department')->first()->id,
            'parent_id' => $department->id,
            'order' => 4,
        ]);


        // -------------------- Account Settings --------------------
        $accountSettings = SidebarItem::create([
            'name' => 'Account Settings',
            'route' => null,
            'permission_id' => Permission::where('name', 'account_settings')->first()->id,
            'parent_id' => null,
            'order' => 12, 
        ]);

        // OS Menu List
        SidebarItem::create([
            'name' => 'OS Menu List',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_os_menu')->first()->id,
            'parent_id' => $accountSettings->id,
            'order' => 1,
        ]);

        // User Settings
        $userSettings = SidebarItem::create([
            'name' => 'User Settings',
            'route' => null,
            'permission_id' => Permission::where('name', 'view_user_settings')->first()->id,
            'parent_id' => $accountSettings->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'List',
            'route' => 'user_settings.index',
            'permission_id' => Permission::where('name', 'list_user_settings')->first()->id,
            'parent_id' => $userSettings->id,
            'order' => 1,
        ]);

        SidebarItem::create([
            'name' => 'New',
            'route' => 'user_settings.create',
            'permission_id' => Permission::where('name', 'create_user_settings')->first()->id,
            'parent_id' => $userSettings->id,
            'order' => 2,
        ]);

        SidebarItem::create([
            'name' => 'Edit',
            'route' => 'user_settings.edit',
            'permission_id' => Permission::where('name', 'edit_user_settings')->first()->id,
            'parent_id' => $userSettings->id,
            'order' => 3,
        ]);

        SidebarItem::create([
            'name' => 'Delete',
            'route' => 'user_settings.delete',
            'permission_id' => Permission::where('name', 'delete_user_settings')->first()->id,
            'parent_id' => $userSettings->id,
            'order' => 4,
        ]);
    }
}
