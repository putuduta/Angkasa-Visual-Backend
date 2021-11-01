<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use App\Models\DetailSkill;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class DesignerController extends Controller
{
    public function update(Request $request) {

        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            $designer = Designer::where('id', '=', $request->designer_id)->first();
            $designer->update([
                'is_approved' => $request->is_approved == "1" ? true : false
            ]);
    
            if ($request->product_id != "") {
                DetailSkill::create([
                    'designer_id' => $request->designer_id,
                    'product_id' => $request->product_id
                ]);
            }
            //Cart created, return success response
            return response()->json([
                'success' => true,
                'message' => 'Updated successfully'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'success' => false,
        ], 404);


    }

    public function findDesignerByProduct($id) {

        return response()->json([
            'success' => true,
            'designers' => DB::table('products')
                ->join('detail_skills', 'detail_skills.product_id', '=', 'products.id')
                ->join('designers', 'designers.id', '=', 'detail_skills.designer_id')
                ->join('users', 'users.id', '=', 'designers.user_id')
                ->select(
                    'users.id as user_id',
                    'users.name',
                    'users.email',
                    'designers.id as designer_id',
                    'designers.resume',
                    'designers.portofolio_link',
                    'designers.skills',
                )->where(
                    'products.id', '=', $id
                )->distinct()->get()
        ]);
    }
    
    public function getdesigners(Request $request) {
        $user = JWTAuth::authenticate($request->token);
        if ($user) {
            return response()->json([
                'success' => true,
                'designers' => DB::table('designers')
                                ->join('users', 'users.id', '=', 'designers.user_id')
                                ->select(
                                    'users.*',
                                    'designers.*' 
                                )->get()
            ]);
        }
        return response()->json([
            'success' => false,
        ], 404);
    }
}

