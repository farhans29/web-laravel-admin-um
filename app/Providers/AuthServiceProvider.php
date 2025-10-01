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
            'Management',
            'view_dashboard',
            'view_bookings',
            'view_all_bookings',
            'view_pending_bookings',
            'view_confirmed_bookings',
            'view_checkins',
            'view_checkouts',
            'view_completed_bookings',
            'view_change_room',
            'properties',
            'view_properties',
            'view_property_facilities',
            'rooms',
            'view_rooms',
            'view_room_facilities',
            'view_customers',
            'financial',
            'view_payments',
            'view_invoices',
            'view_reports',
            'Settings',
            'view_users',
            'manage_roles',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Gate::define($permission, function ($user) use ($permission) {
                return $user->hasPermission($permission);
            });
        }
    }
}
