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
        return DB::table('products')->join('product_packages', 'products.id', '=', 'product_packages.id')
        ->select(
            'products.*',
            'product_packages.*'
        )->get();
    }
}
