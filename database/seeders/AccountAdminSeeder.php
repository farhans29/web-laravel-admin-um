<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Admin',
                'email' => 'admin_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 1, // Admin
            ],
            [
                'name' => 'User',
                'email' => 'user_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 2, // User
            ],
            [
                'name' => 'Purchasing User',
                'email' => 'purchasing_tsno@gmail.com',
                'password' => bcrypt('DigitaLL24$$'),
                'role_id' => 3, // Purchasing
            ],
        ]);
    }
}
