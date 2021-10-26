<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'product_name' => 'Logo Design',
                'product_category' => 'Graphic Design and Editing',
                'product_desc' => 'Test',
                'package_description' => 'Test',
                'package_name' => 'Sky Package',
                'price' => '100.000'
            ]
        ]);
    }
}
