<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Lang;
use Gate;

class HomeController extends Controller
{
	public function index(){
		$recent_problemsets = \App\Problemset::orderBy('updated_at', 'desc')->take(6)->get();
		$home_page_problemsets=[];
		foreach($recent_problemsets as $problemset){
			if($problemset->public || Gate::allows('view',$problemset)){
				array_push($home_page_problemsets,$problemset);
			}
		}

		$top_users = User::orderBy('cnt_ac', 'desc')->take(10)->get();

		return view('home',[
			'home_page_problemsets' => $home_page_problemsets,
			'top_users' => $top_users]);
	}
	public function faq(){
		return view('faq.'.Lang::locale());
	}
}
