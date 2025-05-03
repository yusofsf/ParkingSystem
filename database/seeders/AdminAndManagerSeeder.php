<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAndManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'user_name' => 'admin',
                'full_name' => 'Administrator',
                'role' => 3,
                'email' => 'admin@gmail.com',
                'password' => Hash::make('1234567')
            ],
            [
                'user_name' => 'manager',
                'full_name' => 'Parking Manager',
                'role' => 2,
                'email' => 'manager@gmail.com',
                'password' => Hash::make('123456')
            ]
        ]);
    }
}
