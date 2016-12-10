<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Solution;

class JudgerController extends Controller
{
	public function __construct(){
		$this->middleware('auth');
		$this->middleware('judger');
	}
	public function getIndex(){
		return response()->json(['ok' => true]);
	}
	public function getPendingSolutions(Request $r){
		$solutions = Solution::where('status','<=',1)->get();
		return response()->json($solutions);
	}
}
