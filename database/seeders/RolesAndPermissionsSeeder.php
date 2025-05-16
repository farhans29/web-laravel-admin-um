<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolePermissions = [
            [1, 1],
            [1, 2],
            [1, 3],
        ];

        $now = Carbon::now();

        $data = array_map(fn($rolePermission) => [
            'role_id'     => $rolePermission[0],
            'permission_id' => $rolePermission[1],
            'created_at'  => $now,
            'updated_at'  => $now,
            'created_by' => 1,
            'updated_by' => 1,            
        ], $rolePermissions);

        DB::table('role_permission')->insert($data);
    }
}
