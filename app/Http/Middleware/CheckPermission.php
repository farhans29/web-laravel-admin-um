<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SidebarItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Allow super admin to access everything
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        // Get current route name
        $currentRoute = $request->route()->getName();

        // Skip permission check for certain routes
        $allowedRoutes = [
            'dashboard',
            'login',
            'logout',
            'user.password.update',
            'user.activity',
        ];

        if (in_array($currentRoute, $allowedRoutes)) {
            return $next($request);
        }

        // Find sidebar item by route name
        $sidebarItem = SidebarItem::where('route', $currentRoute)->first();

        // If route not found in sidebar_items, allow access (for non-menu routes)
        if (!$sidebarItem) {
            return $next($request);
        }

        // Get the permission_id from sidebar_item (this is actually sidebar_item.id)
        $permissionId = $sidebarItem->id;

        // Check if user has permission
        $hasPermission = DB::table('role_permission')
            ->where('user_id', $user->id)
            ->where('permission_id', $permissionId)
            ->exists();

        if (!$hasPermission) {
            // Return 403 Forbidden
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
