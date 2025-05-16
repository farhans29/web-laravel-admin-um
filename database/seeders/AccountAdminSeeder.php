<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AccountAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'first_name' => 'Admin',
                'last_name' => 'System',
                'username' => 'admin',
                'email' => 'admin_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 1, // Admin
                'is_admin' => 1,
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'first_name' => 'User',
                'last_name' => 'Standard',
                'username' => 'user',
                'email' => 'user_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 2, // User
                'is_admin' => 0,
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'first_name' => 'Purchasing',
                'last_name' => 'Staff',
                'username' => 'purchasing',
                'email' => 'purchasing_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 3, // Purchasing
                'is_admin' => 0,
                'status' => 'active',
                'created_at' => now(),
            ],
        ]);
    }
}
