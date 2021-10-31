<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'product' => $this->getProducts()
        ]);
    }

    private static function getProducts() {
        return DB::table('products')->join('product_packages', 'products.id', '=', 'product_packages.product_id')
        ->select(
            'products.id',
            'products.product_name',
            'products.product_category',
            'product_packages.id as product_package_id',
            'products.product_desc',
            'products.product_image',
            'product_packages.package_description',            
            'product_packages.package_name',            
            'product_packages.price',            
        )->get();
    }

    
    public function findProductById($id) {

        return response()->json([
            'success' => true,
            'product' => DB::table('products')->join('product_packages', 'products.id', '=', 'product_packages.product_id')
                ->select(
                    'products.id',
                    'products.product_name',
                    'products.product_category',
                    'products.product_desc',
                    'products.product_image',
                    'product_packages.id as product_package_id',
                    'product_packages.package_description',            
                    'product_packages.package_name',            
                    'product_packages.price',   
                )->where(
                    'products.id', '=', $id
                )->get()
        ]);
    }
}
