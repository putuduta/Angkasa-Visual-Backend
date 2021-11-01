<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use App\Models\DetailSkill;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $messages = [
            'required'  => 'Harap bagian :attribute di isi.',
            'unique'    => ':attribute sudah digunakan',
        ];

        //Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'dob' => 'required|string',
            'address' => 'required|string',
            'id_card' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'dob' => $request->dob,
            'address' => $request->address,
            'id_card' => $request->id_card == '' ? null : $request->id_card,
            'is_designer' => $request->is_designer == '1' ? true : false,
            'is_customer' => $request->is_customer == '1' ? true : false,
            'password' => bcrypt($request->password)
        ]);

        //Request is validated
        //Crean token
        try {
            $token = JWTAuth::attempt($request->only('email', 'password'), ['exp' => Carbon::now()->addDays(7)->timestamp]);
            //User created, return success response
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user,
                'token' => $token
            ], Response::HTTP_OK);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error Register user',
            ], 500);
        }
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials, ['exp' => Carbon::now()->addDays(7)->timestamp])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function registerDesigner(Request $request)
    {
        $messages = [
            'required'  => 'Harap bagian :attribute di isi.',
            'unique'    => ':attribute sudah digunakan',
        ];

        //Validate data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone_number' => 'required|string',
            'dob' => 'required|string',
            'address' => 'required|string',
            'id_card' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'bank_account' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'resume' => 'required|string',
            'portofolio_link' => 'required|string',
            'skills' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'dob' => $request->dob,
            'address' => $request->address,
            'id_card' => $request->id_card,
            'is_designer' => $request->is_designer == '1' ? true : false,
            'is_customer' => $request->is_customer == '1' ? true : false,
            'password' => bcrypt($request->password)
        ]);

        $designer = Designer::create([
            'user_id' => $user->id,
            'bank_account' => $request->bank_account,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'resume' => $request->resume,
            'is_approved' => false,
            'portofolio_link' => $request->portofolio_link,
            'skills' => $request->skills
        ]);

        //Request is validated
        //Crean token
        try {
            $token = JWTAuth::attempt($request->only('email', 'password'), ['exp' => Carbon::now()->addDays(7)->timestamp]);
            //User created, return success response
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user,
                'token' => $token
            ], Response::HTTP_OK);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error Register user',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        if ($user->is_designer) {
            $designer = DB::table('designers')
                    ->join('users', 'users.id', '=', 'designers.user_id')
                    ->select(
                        'users.name',
                        'users.email',
                        'users.id_card',
                        'designers.*'   
                    )->where(
                        'users.id', '=', $user->id
                    )->get();
            return response()->json(['user' => $designer]);
        }

        return response()->json(['user' => $user]);
    }
}
