<?php

namespace App\Http\Controllers;

use App\Models\DetailOrder;
use App\Models\HeaderOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;


class TransactionController extends Controller
{
    public function save(Request $request) {

        $user = JWTAuth::authenticate($request->token);
        if ($user) {

            $validator = Validator::make($request->all(), [
                'tanggal_order' => 'required|string',
                'account_name' => 'required|string',
                'account_number' => 'required|string',
                'payment_proof' => 'required|string'
            ]);
    
            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            $headerOrder = HeaderOrder::create([
                'user_id' => $user->id,
                'tanggal_order' => $request->tanggal_order,
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'payment_proof' => $request->payment_proof,
                'payment_status' => $request->payment_status == '1' ? '1' : '0'
            ]);

            $carts = $this->getCarts($user);
            if ($carts) {

                foreach ($carts as $cart) {
                    DetailOrder::create([
                        'designer_id' => $cart->designer_id,
                        'order_id' => $headerOrder->id,
                        'product_package_id' => $cart->product_package_id,
                        'deadline' => $cart->deadline,
                        'status' => '0',
                        'quantity' => $cart->quantity,
                        'request_file_link' => $cart->request_file_link,
                        'notes' => $cart->notes,
                        'result_design' => null
                    ]);
                }

                // Detail order created, return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Success save order',
                    'data' => $headerOrder
                ], Response::HTTP_OK);
            }
            return response()->json([
                'success' => false,
                'message' => 'Error save detail order carts empty'
            ], 500);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }

    public function getOrderList(Request $request) {
        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            $headerOrder = HeaderOrder::where('user_id', $user->id);

            return response()->json([
                'success' => true,
                'headerOrder' => $headerOrder
            ], Response::HTTP_OK);
        }
    }

    public function getOrderByOrderId(Request $request, $id) {
        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            $detailOrders = DB::table('detail_orders')
            ->join('header_orders', 'header_orders.id', '=', 'detail_orders.order_id')
            ->join('designers', 'designers.id', '=', 'detail_orders.designer_id')
            ->join('users', 'users.id', '=', 'designers.user_id')          
            ->join('product_packages', 'product_packages.id', '=', 'detail_orders.product_package_id')
            ->join('products', 'products.id', '=', 'product_packages.product_id')
            ->select(
                'detail_orders.id',
                'detail_orders.product_package_id',
                'products.product_name',
                'products.product_image',
                'product_packages.price',
                'product_packages.package_name',
                'detail_orders.quantity',
                'detail_orders.request_file_link',
                'detail_orders.notes',
                'detail_orders.deadline',
                'detail_orders.designer_id',
                'users.name as designer_name',
                'users.email as designer_email',
                'users.phone_number as designer_phone_number',
            )
            ->where('header_orders.user_id', '=', $user->id)
            ->where('header_orders.order_id', '=', $id)
            ->get();

            $headerOrder = HeaderOrder::where('user_id', $user->id)->where('header_orders.order_id', '=', $id);

            return response()->json([
                'success' => true,
                'detailOrder' => $detailOrders,
                'headerOrder' => $headerOrder,
                'amount' => $detailOrders->sum('price')
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }

    private function getCarts($user) {
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
        ->where('users.id', '=', $user->id)
        ->get();
        return $carts;
    }
}
