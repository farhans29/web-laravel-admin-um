<?php

namespace Database\Seeders;

use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            // HO (Pusat)
            ['name' => 'Super Admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Finance HO', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Manager HO', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            // Site (Cabang Hotel)
            ['name' => 'Site Admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Receptionist', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Housekeeping', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Manager Hotel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
