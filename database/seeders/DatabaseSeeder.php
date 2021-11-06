<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'phone_number' => '082390935',
                'dob' => '05/11/2021',
                'address' => 'Jakarta',
                'is_designer' => '0',
                'is_customer' => '0',
                'is_admin' => '1'
            ]
        ]);
    }
}
