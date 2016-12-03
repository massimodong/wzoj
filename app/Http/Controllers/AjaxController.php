<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Solution;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
	public function getIndex(){
		return response()->json(['welcome'=>'hello world!']);
	}

	public function getPendingSolutions(Request $r){
		$this->authorize('judge',Solution::class);
		$solutions = Solution::where('status','<=',1)->get();
		return response()->json($solutions);
	}
}
