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
                'role_id' => 1, // Owner
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'User',
                'last_name' => 'Standard',
                'username' => 'user',
                'email' => 'user_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 2, // Manager
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Purchasing',
                'last_name' => 'Staff',
                'username' => 'purchasing',
                'email' => 'purchasing_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 3, // Front Desk
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Farhan',
                'last_name' => '',
                'username' => 'farhan',
                'email' => 'm.farhanshihab11@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 4, // Finance
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Farhans',
                'last_name' => '',
                'username' => 'farhans299',
                'email' => 'farhans29@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 5, // CS
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Hadrian',
                'last_name' => '',
                'username' => 'hadrian',
                'email' => 'hadriannaufal10@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 6, // Sales
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Vin',
                'last_name' => 'User',
                'username' => 'vin123',
                'email' => 'vin123@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 7, // Property Owner
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
            [
                'first_name' => 'Vincent',
                'last_name' => '',
                'username' => 'vincent',
                'email' => 'vincent.code7@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 2, // Manager
                'is_admin' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
