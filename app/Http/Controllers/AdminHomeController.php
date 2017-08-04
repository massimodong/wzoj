<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Option;
use Cache;

class AdminHomeController extends Controller
{
	public function index(){
		return view('admin.home');
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

	public function flushCache(){
		\Redis::command('flushall');
		return back();
	}
}
