<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cache;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
	public function getTopUsers(Request $request){
		$top_users = Cache::tags(['wzoj'])->remember('top_users', 1, function(){
			return \App\User::orderBy('cnt_ac', 'desc')
				->take(10)
				->withoutAdmin()
				->select(['id', 'name', 'description', 'cnt_ac'])
				->get();
		});
		return response()->json(['data' => $top_users]);
	}
}
