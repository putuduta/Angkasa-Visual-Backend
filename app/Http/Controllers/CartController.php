<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class CartController extends Controller
{
    public function index(Request $request) {
        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            $carts = $this->getUserCarts($user);

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
    
    private function getUserCarts($user) {
        $carts = DB::table('carts')
            ->join('users', 'users.id', '=', 'carts.user_id')
            ->leftJoin('designers', 'designers.id', '=', 'carts.designer_id')
            ->leftJoin('users as us2', 'us2.id', '=', 'designers.user_id')
            ->join('product_packages', 'product_packages.id', '=', 'carts.product_package_id')
            ->join('products', 'products.id', '=', 'product_packages.product_id')
            ->select(
                'carts.id',
                'products.product_name',
                'products.product_image',
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
            ->where('users.id', '=', $user->id)
            ->get();
            
            return $carts;
    }

    public function save(Request $request) {

        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            Cart::create([
                'user_id' => $user->id,
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
    }
    
    
    public function delete(Request $request) {
        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            Cart::where('id', $request->id)->delete();
            //Cart created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Cart deleted successfully'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }
}
