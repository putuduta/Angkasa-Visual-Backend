<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ChatController extends Controller
{

    public function index(Request $request) {
        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            $messages = $this->geMessage($request);

            return response()->json([
                'success' => true,
                'data' => $messages,
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }

    private function geMessage(Request $request) {
        $messages = DB::table('chats')
            ->join('users', 'users.id', '=', 'chats.user_id')
            ->join('designers', 'designers.id', '=', 'chats.designer_id')
            ->join('users as us2', 'us2.id', '=', 'designers.user_id')
            ->join('detail_orders', 'detail_orders.id', '=', 'chats.detail_order_id')
            ->select(
                'chats.id as chat_id',
                'us2.name as designer_name',
                'us2.email as designer_email',
                'us2.phone_number as designer_phone_number',
                'chats.detail_order_id as detail_order_id',
                'chats.message as message',
                'chats.created_at',
            )
            ->where('chats.detail_order_id', '=', $request->detail_order_id)
            ->where('chats.is_designer', '=', $request->is_designer)
            ->orderBy('chats.id', 'desc')
            ->get();
            
            return $messages;
    }

    public function save(Request $request) {

        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            Chat::create([
                'user_id' => $user->id,
                'detail_order_id' => $request->detail_order_id,
                'designer_id' => $request->designer_id,
                'message' => $request->message,
                'is_designer' => $request->is_designer == '1' ? true : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message Send'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
        ], 404);
    }
}
