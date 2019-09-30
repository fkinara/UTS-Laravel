<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Saldo;
use App\User;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SaldoController extends Controller
{
    // public function saldo() {
    // 	$data = "data saldo";
    // 	return response()->json($data, 200);
    // }

    // public function saldoAuth() {
    // 	$data = "hello". Auth::user()->name;
    // 	return response()->json($data, 200);
 
    // }
	public function index()
	{
		$data = saldo::all();
		return $data;
	}

    public function store(Request $request)
    {
    	try{
    		if (! $user = JWTAuth::ParseToken()->authenticate())
    		{
    			return response()->json(['user_not_found'], 404);
    		} 
    		$user = user::where('id', $user['id'])->first();
    		if($request->jenis=='kredit'){
    			if($user->jumlah_saldo < $request->input('jumlah_saldo')){
    				return response()->json(['saldo kurang'], 400);
    			}
    		} else if($request->jenis != 'debit') {
					return response()->json(['error'=>'jenis salah'], 400);
    		}

    		$data = new Saldo();
    		$data->username = $user ['username'];
	   		$data->jenis = $request->input('jenis');
	   		$data->nama_transaksi = $request->input('nama_transaksi');
			$data->jumlah_saldo=$request->input('jumlah_saldo');
	   		$data->save();
	   		if ($request->jenis=='debit'){
	   			$user->jumlah_saldo =$user->jumlah_saldo + $request->input('jumlah_saldo');
	   		} else{
	   			$user->jumlah_saldo =$user->jumlah_saldo - $request->input('jumlah_saldo');
	   		}
	   		$user->save();
	   		return response()->json(compact('data', 'user'));
    	} catch(\Exception $e){
    		// return response()->json([
    		// 	'status' => '0', 'message' => 'gagal menambah'
    		// ]);
    	}
	}

    public function update(Request $request)
    {
    	try{
    		if(! $akun = JWTAuth::parseToken()->authenticate()) {
    			return response()->json(['user_not_found'], 404);
    		}
    		$user = user::where('id', $akun['id'])->first();
    		$data = new Saldo();
    		$data->username = $akun ['username'];
	   		$data->jenis = $request->input('jenis');
	   		$data->nama_transaksi = $request->input('nama_transaksi');
	   		$data->jumlah_saldo = $request->input('jumlah_saldo');
	   		$data->save();
	   		$akun->saldo =$akun->saldo + $request->input('jumlah_saldo');
	   		$data->save();
	   		return response()->json(compact('data', 'user'));
    	}  catch(\Exception $e){
    		// return response()->json([
    		// 	'status' => '0', 'message' => 'gagal menambah'
    		// ]);
    }
    }
}
