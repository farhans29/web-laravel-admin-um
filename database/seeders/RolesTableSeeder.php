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
            ['name' => 'Owner', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Manager', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Front Desk', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Finance', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'CS', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Sales', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Property Owner', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
