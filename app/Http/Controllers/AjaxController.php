<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Auth;
use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
	public function getIndex(){
		return response()->json(['welcome'=>'hello world!']);
	}

	public function getRoles(Request $request){
		if(!Auth::check()){
			return response()->json(NULL);
		}else{
			return response()->json($request->user()->roles);
		}
	}

	public function getToken(Request $request){
		return response()->json(['_token' => csrf_token()]);
	}

	public function postTest(Request $request){
		return $request->all();
	}
}
