<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    protected $user;
    public function __construct()
    {
        if (JWTAuth::getToken())
            $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index() {
        if (JWTAuth::getToken()) {
            $carts = DB::table('carts')
            ->join('users', 'users.id', '=', 'carts.user_id')
            ->join('designers', 'designers.id', '=', 'carts.designer_id')
            ->join('users as us2', 'us2.id', '=', 'designers.user_id')
            ->join('product_packages', 'product_packages.id', '=', 'carts.product_package_id')
            ->join('products', 'products.id', '=', 'product_packages.product_id')
            ->select(
                'products.product_name',
                'product_packages.price',
                'product_packages.package_name',
                'carts.quantity',
                'carts.request_file_link',
                'carts.notes',
                'carts.deadline',
                'carts.designer_id',
                'us2.name as designer_name',
                'us2.email as designer_email',
                'us2.phone_number as designer_phone_number',
                'carts.product_package_id',
            )
            ->where('users.id', '=', $this->user->id)
            ->get();

            return response()->json([
                'success' => true,
                'data' => $carts,
                'amount' => $carts->sum('price')
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }

    public function save(Request $request) {
        try {
            if (JWTAuth::getToken()) {
                Cart::create([
                    'user_id' => $this->user->id,
                    'product_package_id' => $request->package_id,
                    'designer_id' => $request->designer_id == "" ? null : $request->designer_id,
                    'request_file_link' => $request->request_file_link,
                    'quantity' => $request->quantity,
                    'notes' => $request->notes,
                    'deadline' => $request->deadline,
                ]);
                //Cart created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Cart created successfully'
                ], Response::HTTP_OK);
            }
    
            return response()->json([
                'success' => false,
            ], 404);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error create cart',
            ], 500);
        }
    }
}
