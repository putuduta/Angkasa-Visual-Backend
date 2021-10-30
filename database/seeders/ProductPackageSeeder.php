<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_packages')->insert([
            [
                'product_id' => '1',
                'package_description' => "1 Konsep logo\r\nRevisi 3 kali max\r\n4-5 hari\r\nExport bentuk PNG/JPEG/PDF sesuai request\r\nColor Guide\r\nFree konsult dan nego dengan designer",
                'package_name' => 'Sky Package',
                'price' => '100.000'
            ],
            [
                'product_id' => '2',
                'package_description' => "1 Business card design\r\nRevisi 3 kali max\r\n1-2 hari\r\nExport bentuk PNG/JPEG/PDF sesuai request\r\nColor Guide\r\nFree konsult dan nego dengan designer",
                'package_name' => 'Business Card Only',
                'price' => '35000'        
            ]
        ]);
    }
}
