<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserMember;
use Validator;

class AuthController extends Controller
{
     /**
    *
    * @var Validator
    */
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;

       }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator::make($request->all(), [
            'lastname' => 'required|string|max:255|min:2',
            'firstname' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255|unique:users',
            'password'=> 'required',
            'profil_id' => 'required',
            // 'phonenumber' => 'required',
            // 'status' => 'required',
            'payment_method_id' =>'required',
            'employe_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = UserMember::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'profil_id' => $request->profil_id,
            'payment_method_id' => $request->payment_method_id,
            'employe_id' => $request->employe_id,
            'phonenumber' => $request->phonenumber,
            'status' => $request->status,
        ]);

        $token = auth()->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 201);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $validator = $this->validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $current_user = $request->email;

        return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'current_user' => $current_user,
        'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout(true); // Force token to blacklist
        return response()->json(['success' => 'Logged out Successfully.'], 200);

    }

}
