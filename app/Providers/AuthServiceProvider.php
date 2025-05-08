<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // $this->registerPolicies();

        $permissions = [
            // Purchasing
            'purchasing',
            'view_purchase_request',
            'list_purchase_request',
            'create_purchase_request',
            'edit_purchase_request',
            'submit_purchase_request',
            // Sales
            'sales',
            'view_promotions',
            'list_promotions',
            'create_promotions',
            'edit_promotions',
            'delete_promotions',
            // Warehouse
            'warehouse',
            'view_inventory',
            'list_inventory',
            'create_inventory',
            'edit_inventory',
            'delete_inventory',
            // Accounting
            'accounting',
            'view_accounts',
            'list_accounts',
            'create_accounts',
            'edit_accounts',
            'delete_accounts',
            'view_invoice',
            'list_invoice',
            'create_invoice',
            'edit_invoice',
            'delete_invoice',
            // Finance
            'finance',
            'view_payment',
            'list_payments',
            'create_payments',
            'edit_payments',
            'delete_payments',
            'view_reports',
            'list_reports',
            'create_reports',
            'edit_reports',
            'delete_reports',
            // Tax
            'tax',
            'view_tax_payment',
            'list_tax_payments',
            'create_tax_payments',
            'edit_tax_payments',
            'delete_tax_payments',
            // 'view_tax_reports',
            // 'list_tax_reports',
            // 'create_tax_reports',
            // 'edit_tax_reports',
            // 'delete_tax_reports',
            // Human Resource
            'human_resource',
            'view_employees',
            'list_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',
            'view_payroll',
            'list_payroll',
            'create_payroll',
            'edit_payroll',
            'delete_payroll',
            // General Affairs
            'general_affairs',
            'view_office_supplies',
            'list_office_supplies',
            'create_office_supplies',
            'edit_office_supplies',
            'delete_office_supplies',
            'view_facilities',
            'list_facilities',
            'create_facilities',
            'edit_facilities',
            'delete_facilities',
            // Logistics
            'logistics',
            'view_shipping',
            'list_shipping',
            'create_shipping',
            'edit_shipping',
            'delete_shipping',
            // Archive
            'aju',
            'view_aju',
            'list_aju',
            'create_aju',
            'edit_aju',
            'delete_aju',
            'view_document',
            'list_document',
            'create_document',
            'edit_document',
            'delete_document',
            // Master
            'master',
            'view_master_department',
            'list_master_department',
            'create_master_department',
            'edit_master_department',
            'delete_master_department',
            // Account Settings
            'account_settings',
            'view_os_menu',
            'view_user_settings',
            'list_user_settings',
            'create_user_settings',
            'edit_user_settings',
            'delete_user_settings',
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }
}
