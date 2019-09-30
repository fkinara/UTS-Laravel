<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function login(Request $request)
    {
    	$credentials = $request->only('username','password');
    	try {
    		if (! $token = JWTAuth::attempt($credentials)) {
    			return response()->json(['error' => 'invalid_credentials'], 400);
    		} 
    	} catch (JWTException $e) {
    		return response()->json(['error' => 'could_not_create_token'], 500);
    	}
    	return response()->json(compact('token'));
    }

    public function register(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    		'username' => 'required|string|max:225|unique:users',
    		'password' => 'required|string',
    		'jumlah_saldo' => 'integer',
    	]);
    	if ($validator->fails()) {
    		return response()->json($validator->errors()->toJson(), 400);
    	}
    	$user = User::create([
    		'username' => $request->get('username'),
    		'password' => Hash::make($request->get('password')),
    		'jumlah_saldo' => $request->get('jumlah_saldo'),
    	]);
    	$token = JWTAuth::fromUser($user);
    	return response()->json(compact('user', 'token'), 201);
    }
   
    public function updateSaldo (Request $request)
    {
    	$username = $request -> username;
    	$user = JWTAuth::parseToken()->authenticate();
    	$user->jumlah_saldo=$user->jumlah_saldo + $request->input('jumlah_saldo');
    	$user->save();
    	// $pesan->"berhasil";
    	return response()->json(compact('user'));
	}
}