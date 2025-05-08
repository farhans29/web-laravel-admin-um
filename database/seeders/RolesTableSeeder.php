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
            ['name' => 'admin', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'user', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'purchasing', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'sales', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'warehouse', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'accounting', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'finance', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'tax', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'hr', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'ga', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'logistics', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
