<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Option;
use Cache;

class AdminHomeController extends Controller
{
	public function __construct(){
		$this->middleware('role:admin', ['except' => ['index']]);
	}

	public function index(Request $request){
		if($request->user()->has_role('admin')) return view('admin.home');
		else return view('admin.manager_home');
	}

	public function postOptions(Request $request){
		$this->validate($request, [
			'sim_threshold' => 'integer|min:1|max:100',
		]);
		foreach($request->except(['_token']) as $option => $value){
			Option::where('name', $option)->update(['value' => $value]);
		}
		Cache::tags(['options'])->flush();
		return back();
	}
}
