<?php

namespace App\Http\Controllers;

use App\Models\Designer;
use App\Models\DetailSkill;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
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
            'id_card' => 'nullable|image|max:100|mimes:jpg',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        if (request()->hasFile('id_card')) {
            $extension = request()->file('id_card')->getClientOriginalExtension();
            $idCardFileName = $request->name . '_id_card_' . time() . '.' . $extension;
            // request()->file('cover')->storeAs('public/assets/id-card', $idCardFileName);
            $request->id_card->move(public_path('/id-card'), $idCardFileName);
        } else {
            $idCardFileName = NULL;
        }

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
            $token = JWTAuth::attempt($request->only('email', 'password'));
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
            if (! $token = JWTAuth::attempt($credentials)) {
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
            'id_card' => 'nullable|image|max:100|mimes:jpg',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'bank_account' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'resume' => 'nullable|image|max:100|mimes:jpg',
            'portofolio_link' => 'required|string',
            'product_id' => 'required',
        ]);

        if (request()->hasFile('id_card')) {
            $extension = request()->file('id_card')->getClientOriginalExtension();
            $idCardFileName = $request->name . '_id_card_' . time() . '.' . $extension;
            // request()->file('cover')->storeAs('public/assets/id-card', $idCardFileName);
            $request->id_card->move(public_path('/id-card'), $idCardFileName);
        } else {
            $idCardFileName = NULL;
        }

        
        if (request()->hasFile('resume')) {
            $extension = request()->file('resume')->getClientOriginalExtension();
            $resumeFileName = $request->name . 'resume' . time() . '.' . $extension;
            // request()->file('cover')->storeAs('public/assets/id-card', $idCardFileName);
            $request->resume->move(public_path('/resume'), $resumeFileName);
        } else {
            $resumeFileName = NULL;
        }


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

        $designer = Designer::create([
        	'user_id' => $user->id,
        	'bank_account' => $request->bank_account,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'resume' => "Null",
            'portofolio_link' => $request->portofolio_link
        ]);

        DetailSkill::create([
            'designer_id' => $designer->id,
            'product_id' => $request->product_id
        ]);

        //Request is validated
        //Crean token
        try {
            $token = JWTAuth::attempt($request->only('email', 'password'));
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
 
        return response()->json(['user' => $user]);
    }
}
