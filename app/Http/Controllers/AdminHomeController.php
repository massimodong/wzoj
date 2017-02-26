<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Option;

class AdminHomeController extends Controller
{
	public function index(){
		return view('admin.home');
	}

	public function postOptions(Request $request){
		foreach($request->except(['_token']) as $option => $value){
			Option::where('name', $option)->update(['value' => $value]);
		}
		return back();
	}
}
